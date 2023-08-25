<?php

namespace Akeneo\Crowdin\Api;

use Akeneo\Crowdin\Client;
use Akeneo\Crowdin\FileReader;
use Akeneo\Crowdin\Translation;
use InvalidArgumentException;

/**
 * Adds a new file to a Crowdin project.
 *
 * @author Julien Janvier <j.janvier@gmail.com>
 * @see    https://crowdin.com/page/api/add-file
 */
class AddFile extends AbstractApi
{
    /** @var Translation[] */
    protected array $translations;
    protected ?string $type;
    protected ?string $branch;

    public function __construct(Client $client, protected FileReader $fileReader)
    {
        parent::__construct($client);
    }

    /**
     * {@inheritdoc}
     */
    public function execute(): string
    {
        if (0 === count($this->translations)) {
            throw new InvalidArgumentException('There is no files to add.');
        }

        $this->addUrlParameter('key', $this->client->getProjectApiKey());

        $path = sprintf(
            "project/%s/add-file?%s",
            $this->client->getProjectIdentifier(),
            $this->getUrlQueryString()
        );

        $data = $this->parameters;
        if (null !== $this->type) {
            $data[] = [
                'name' => 'type',
                'contents' => $this->type,
            ];
        }
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
        ?string $exportPattern = null,
        ?string $title = null
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getBranch(): ?string
    {
        return $this->branch;
    }

    public function setBranch(string $branch): static
    {
        $this->branch = $branch;

        return $this;
    }
}
