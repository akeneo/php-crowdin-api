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
 * @see https://crowdin.com/page/api/upload-translation
 */
class UploadTranslation extends AbstractApi
{
    /** @var FileReader */
    protected $fileReader;

    /** @var Translation[] */
    protected $translations;

    /** @var string */
    protected $locale;

    /** @var bool */
    protected $areDuplicatesImported = false;

    /** @var bool */
    protected $areEqualSuggestionsImported = false;

    /** @var bool */
    protected $areImportsAutoApproved = false;
    
    /** @var string */
    protected $branch;

    /**
     * @param Client     $client
     * @param FileReader $fileReader
     */
    public function __construct(Client $client, FileReader $fileReader)
    {
        parent::__construct($client);
        $this->fileReader = $fileReader;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
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
            'name'      => 'import_duplicates',
            'contents'  => (int)$this->areDuplicatesImported
        ];
        $data[] = [
            'name'      => 'import_eq_suggestions',
            'contents'  => (int)$this->areEqualSuggestionsImported
        ];
        $data[] = [
            'name'      => 'auto_approve_imported',
            'contents'  => (int)$this->areImportsAutoApproved
        ];
        $data[] = [
            'name'      => 'language',
            'contents'  => $this->locale
        ];
        
        if (null !== $this->branch) {
            $data[] = [
                'name'      => 'branch',
                'contents'  => $this->branch
            ];
        }

        foreach ($this->translations as $translation) {
            $data[] = [
                'name'       => 'files['.$translation->getCrowdinPath().']',
                'contents'   => $this->fileReader->readTranslation($translation)
            ];
        }

        $data = ['multipart' => $data];
        $response = $this->client->getHttpClient()->post($path, $data);

        return $response->getBody();
    }

    /**
     * @param string $localPath
     * @param string $crowdinPath
     * @param string $exportPattern
     * @param string $title
     *
     * @return $this
     */
    public function addTranslation($localPath, $crowdinPath, $exportPattern = null, $title = null)
    {
        $translation = new Translation($localPath, $crowdinPath);
        $translation->setExportPattern($exportPattern);
        $translation->setTitle($title);

        $this->translations[] = $translation;

        return $this;
    }
    
    /**
     * @param Translation[] $translations
     * @return UploadTranslation
     */
    public function setTranslations(array $translations)
    {
        $this->translations = $translations;
        
        return $this;
    }

    /**
     * @return Translation[]
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * @param bool $importsAutoApproved
     *
     * @throws InvalidArgumentException
     *
     * @return UploadTranslation
     */
    public function setImportsAutoApproved($importsAutoApproved)
    {
        if (!is_bool($importsAutoApproved)) {
            throw new InvalidArgumentException('A boolean is required.');
        }

        $this->areImportsAutoApproved = $importsAutoApproved;

        return $this;
    }

    /**
     * @return bool
     */
    public function areImportsAutoApproved()
    {
        return $this->areImportsAutoApproved;
    }

    /**
     * @param bool $duplicatesImported
     *
     * @throws InvalidArgumentException
     *
     * @return UploadTranslation
     */
    public function setDuplicatesImported($duplicatesImported)
    {
        if (!is_bool($duplicatesImported)) {
            throw new InvalidArgumentException('A boolean is required.');
        }

        $this->areDuplicatesImported = $duplicatesImported;

        return $this;
    }

    /**
     * @return bool
     */
    public function areDuplicatesImported()
    {
        return $this->areDuplicatesImported;
    }

    /**
     * @param bool $equalSuggestionsImported
     *
     * @throws InvalidArgumentException
     *
     * @return UploadTranslation
     */
    public function setEqualSuggestionsImported($equalSuggestionsImported)
    {
        if (!is_bool($equalSuggestionsImported)) {
            throw new InvalidArgumentException('A boolean is required.');
        }

        $this->areEqualSuggestionsImported = $equalSuggestionsImported;

        return $this;
    }

    /**
     * @return bool
     */
    public function areEqualSuggestionsImported()
    {
        return $this->areEqualSuggestionsImported;
    }

    /**
     * @param string $locale
     *
     * @return UploadTranslation
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @return string|null
     */
    public function getBranch()
    {
        return $this->branch;
    }

    /**
     * @param string $branch
     *
     * @return UploadTranslation
     */
    public function setBranch($branch)
    {
        $this->branch = $branch;

        return $this;
    }
}
