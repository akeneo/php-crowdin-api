<?php

namespace Crowdin\Api;

/**
 *  Upload existing translations to your Crowdin project.
 *
 * @author Julien Janvier <j.janvier@gmail.com>
 * @see https://crowdin.net/page/api/upload-translation
 */
class UploadTranslation extends AbstractApi
{
    /**
     * @var array
     */
    protected $translations;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var bool
     */
    protected $areDuplicatesImported = false;

    /**
     * @var bool
     */
    protected $areEqualSuggestionsImported = false;

    /**
     * @var bool
     */
    protected $areImportsAutoApproved = false;

    /**
     * @return mixed
     */
    public function execute()
    {
        if (count($this->getTranslations()) === 0) {
            throw new \InvalidArgumentException('There are no translations to upload !');
        }

        if (null === $this->getLocale()) {
            throw new \InvalidArgumentException('Locale is not set !');
        }

        $path = sprintf(
            "project/%s/upload-translation?key=%s",
            $this->client->getProjectIdentifier(),
            $this->client->getProjectApiKey()
        );

        $data = array_merge($this->parameters, array(
            'import_duplicates' => (int)$this->areDuplicatesImported(),
            'import_eq_suggestions' => (int)$this->areEqualSuggestionsImported(),
            'auto_approve_imported' => (int)$this->areImportsAutoApproved(),
            'language' => $this->getLocale(),
        ));

        foreach ($this->translations as $crowdinPath => $localFile) {
            $data['files['.$crowdinPath.']'] = '@'.$localFile;
        }


        $request = $this->client->getHttpClient()->post($path, array(), $data);
        $response = $request->send();

        return $response->getBody(true);
    }

    /**
     * @param string $crowdinPath the Crowdin file path
     * @param string $localPath   the local file path
     *
     * @throws \InvalidArgumentException
     * @return UploadTranslation
     */
    public function addTranslation($crowdinPath, $localPath)
    {
        if (!file_exists($localPath)) {
            throw new \InvalidArgumentException(sprintf('File %s does not exist !', $localPath));
        }
        $this->translations[$crowdinPath] = $localPath;

        return $this;
    }

    /**
     * @return array
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * @param $shouldAutoApproveImported
     * @return UploadTranslation
     * @throws \InvalidArgumentException
     */
    public function setImportsAutoApproved($shouldAutoApproveImported)
    {
        if (!is_bool($shouldAutoApproveImported)) {
            throw new \InvalidArgumentException('A boolean is required !');
        }

        $this->areImportsAutoApproved = $shouldAutoApproveImported;

        return $this;
    }

    /**
     * @return boolean
     */
    public function areImportsAutoApproved()
    {
        return $this->areImportsAutoApproved;
    }

    /**
     * @param boolean $shouldImportDuplicates
     * @return UploadTranslation
     * @throws \InvalidArgumentException
     */
    public function setDuplicatesImported($shouldImportDuplicates)
    {
        if (!is_bool($shouldImportDuplicates)) {
            throw new \InvalidArgumentException('A boolean is required !');
        }

        $this->areDuplicatesImported = $shouldImportDuplicates;

        return $this;
    }

    /**
     * @return boolean
     */
    public function areDuplicatesImported()
    {
        return $this->areDuplicatesImported;
    }

    /**
     * @param boolean $shouldImportEqualSuggestions
     * @return UploadTranslation
     * @throws \InvalidArgumentException
     */
    public function setEqualSuggestionsImported($shouldImportEqualSuggestions)
    {
        if (!is_bool($shouldImportEqualSuggestions)) {
            throw new \InvalidArgumentException('A boolean is required !');
        }

        $this->areEqualSuggestionsImported = $shouldImportEqualSuggestions;

        return $this;
    }

    /**
     * @return boolean
     */
    public function areEqualSuggestionsImported()
    {
        return $this->areEqualSuggestionsImported;
    }

    /**
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

}
