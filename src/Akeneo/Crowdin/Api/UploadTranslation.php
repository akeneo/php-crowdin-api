<?php

namespace Akeneo\Crowdin\Api;

use \InvalidArgumentException;
use Akeneo\Crowdin\Client;
use Akeneo\Crowdin\FileReader;
use Akeneo\Crowdin\Translation;

/**
 *  Upload existing translations to your Crowdin project.
 *
 * @author Julien Janvier <j.janvier@gmail.com>
 * @see    https://crowdin.com/page/api/upload-translation
 */
class UploadTranslation extends AbstractApi
{
    protected FileReader $fileReader;

    /** @var Translation[] */
    protected array $translations = [];

    protected ?string $locale = null;

    protected bool $areDuplicatesImported = false;
    protected bool $areEqualSuggestionsImported = false;
    protected bool $areImportsAutoApproved = false;
    protected ?string $branch = null;

    public function __construct(Client $client, FileReader $fileReader)
    {
        parent::__construct($client);
        $this->fileReader = $fileReader;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(): string
    {
        if (0 === count($this->translations)) {
            throw new InvalidArgumentException('There are no translations to upload.');
        }

        if (null === $this->locale) {
            throw new InvalidArgumentException('Locale is not set.');
        }

        $this->addUrlParameter('key', $this->client->getProjectApiKey());

        $path = sprintf(
            "project/%s/upload-translation?%s",
            $this->client->getProjectIdentifier(),
            $this->getUrlQueryString()
        );

        $data[] = [
            'name' => 'import_duplicates',
            'contents' => (int)$this->areDuplicatesImported,
        ];
        $data[] = [
            'name' => 'import_eq_suggestions',
            'contents' => (int)$this->areEqualSuggestionsImported,
        ];
        $data[] = [
            'name' => 'auto_approve_imported',
            'contents' => (int)$this->areImportsAutoApproved,
        ];
        $data[] = [
            'name' => 'language',
            'contents' => $this->locale,
        ];

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
     * @param Translation[] $translations
     */
    public function setTranslations(array $translations): static
    {
        $this->translations = $translations;

        return $this;
    }

    /**
     * @return Translation[]
     */
    public function getTranslations(): array
    {
        return $this->translations;
    }

    /**
     * @throws InvalidArgumentException
     *
     */
    public function setImportsAutoApproved(bool $importsAutoApproved): static
    {
        $this->areImportsAutoApproved = $importsAutoApproved;

        return $this;
    }

    public function areImportsAutoApproved(): bool
    {
        return $this->areImportsAutoApproved;
    }

    /**
     * @throws InvalidArgumentException
     *
     */
    public function setDuplicatesImported(bool $duplicatesImported): static
    {
        $this->areDuplicatesImported = $duplicatesImported;

        return $this;
    }

    public function areDuplicatesImported(): bool
    {
        return $this->areDuplicatesImported;
    }

    /**
     * @throws InvalidArgumentException
     *
     */
    public function setEqualSuggestionsImported(bool $equalSuggestionsImported): static
    {
        $this->areEqualSuggestionsImported = $equalSuggestionsImported;

        return $this;
    }

    public function areEqualSuggestionsImported(): bool
    {
        return $this->areEqualSuggestionsImported;
    }

    public function setLocale(string $locale): static
    {
        $this->locale = $locale;

        return $this;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
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
