<?php

namespace Akeneo\Crowdin\Api;

use InvalidArgumentException;
use Akeneo\Crowdin\Client;
use Akeneo\Crowdin\FileReader;
use Akeneo\Crowdin\Translation;

/**
 * Upload latest version of your localization file to Crowdin.
 *
 * @author Nicolas Dupont <nicolas@akeneo.com>
 * @see    https://crowdin.com/page/api/update-file
 */
class UpdateFile extends AbstractApi
{
    protected FileReader $fileReader;

    /** @var Translation[] */
    protected array $translations;
    protected ?string $branch = null;

    public function __construct(Client $client, FileReader $fileReader)
    {
        parent::__construct($client);
        $this->fileReader = $fileReader;
    }

    public function execute(): string
    {
        if (count($this->translations) === 0) {
            throw new InvalidArgumentException('There is no files to update');
        }

        $this->addUrlParameter('key', $this->client->getProjectApiKey());

        $path = sprintf(
            "project/%s/update-file?%s",
            $this->client->getProjectIdentifier(),
            $this->getUrlQueryString()
        );

        $data = $this->parameters;
        if (null !== $this->branch) {
            $data[] = [
                'name' => 'branch',
                'contents' => $this->branch,
            ];
        }

        foreach ($this->translations as $translation) {
            $data[] = [
                'name' => 'files[' . $translation->getCrowdinPath() . ']',
                'contents' => $this->fileReader->readTranslation($translation),
            ];
            if ($translation->getTitle()) {
                $data[] = [
                    'name' => 'titles[' . $translation->getCrowdinPath() . ']',
                    'contents' => $translation->getTitle(),
                ];
            }
            if ($translation->getExportPattern()) {
                $data[] = [
                    'name' => 'export_patterns[' . $translation->getCrowdinPath() . ']',
                    'contents' => $translation->getExportPattern(),
                ];
            }
        }

        $data = ['multipart' => $data];
        $response = $this->client->getHttpClient()->request('POST', $path, $data);

        return $response->getContent();
    }

    public function addTranslation(
        string $localPath,
        string $crowdinPath,
        string $exportPattern = null,
        string $title = null
    ): static {
        $translation = new Translation($localPath, $crowdinPath);
        $translation->setExportPattern($exportPattern);
        $translation->setTitle($title);

        $this->translations[] = $translation;

        return $this;
    }

    /**
     * @return Translation[]
     */
    public function getTranslations(): array
    {
        return $this->translations;
    }

    public function getBranch(): ?string
    {
        return $this->branch;
    }

    public function setBranch(string $branch): void
    {
        $this->branch = $branch;
    }
}
