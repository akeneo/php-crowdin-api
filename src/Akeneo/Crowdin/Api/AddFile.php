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
    protected array $translations = [];
    protected ?string $type = null;
    protected ?string $branch = null;

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

        $path = sprintf(
            "project/%s/add-file",
            $this->client->getProjectIdentifier()
        );

        $data = $this->parameters;
        if (null !== $this->type) {
            $data['type'] = $this->type;
        }
        if (null !== $this->branch) {
            $data['branch'] = $this->branch;
        }
        foreach ($this->translations as $translation) {
            $data[sprintf('files[%s]', $translation->getCrowdinPath())] = $this->fileReader->readTranslation($translation);
            if ($translation->getTitle()) {
                $data[sprintf('titles[%s]', $translation->getCrowdinPath())] = $translation->getTitle();
            }
            if ($translation->getExportPattern()) {
                $data[sprintf('export_patterns[%s]', $translation->getCrowdinPath())] = $translation->getExportPattern();
            }
        }

        $data = [
            'headers' => [
                'Content-Type' => 'multipart/form-data',
                'Authorization' => 'Bearer 1234'
            ],
            'body' => $data
        ];
        $response = $this->client->getHttpClient()->request('POST', $path, $data);

        return $response->getContent();
    }

    public function addTranslation(
        string  $localPath,
        string  $crowdinPath,
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
