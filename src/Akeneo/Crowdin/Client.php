<?php

namespace Akeneo\Crowdin;

use \InvalidArgumentException;
use GuzzleHttp\Client as HttpClient;

/**
 * Simple Crowdin PHP client
 *
 * @author Nicolas Dupont <nicolas@akeneo.com>
 */
class Client
{
    /** @var string base url */
    const BASE_URL = 'https://api.crowdin.com/api/';

    /** @var HttpClient */
    protected $httpClient;

    /** @var string the project identifier */
    protected $projectIdentifier;

    /** @var string the project api key */
    protected $projectApiKey;

    /**
     * Instantiate a new Crowdin Client
     *
     * @param string $identifier the project identifier
     * @param string $apiKey     the project api key
     */
    public function __construct($identifier, $apiKey)
    {
        $this->projectIdentifier = $identifier;
        $this->projectApiKey     = $apiKey;
    }

    /**
     * @param string $method the api method
     *
     * @throws InvalidArgumentException
     *
     * @return mixed
     */
    public function api($method)
    {
        $fileReader = new FileReader();
        switch ($method) {
            case 'info':
                $api = new Api\Info($this);
                break;
            case 'supported-languages':
                $api = new Api\SupportedLanguages($this);
                break;
            case 'status':
                $api = new Api\Status($this);
                break;
            case 'download':
                $api = new Api\Download($this);
                break;
            case 'add-file':
                $api = new Api\AddFile($this, $fileReader);
                break;
            case 'update-file':
                $api = new Api\UpdateFile($this, $fileReader);
                break;
            case 'delete-file':
                $api = new Api\DeleteFile($this);
                break;
            case 'export':
                $api = new Api\Export($this);
                break;
            case 'add-directory':
                $api = new Api\AddDirectory($this);
                break;
            case 'delete-directory':
                $api = new Api\DeleteDirectory($this);
                break;
            case 'upload-translation':
                $api = new Api\UploadTranslation($this, $fileReader);
                break;
            case 'language-status':
                $api = new Api\LanguageStatus($this);
                break;
            default:
                throw new InvalidArgumentException(sprintf('Undefined api method "%s"', $method));
        }

        return $api;
    }

    /**
     * @return string
     */
    public function getProjectIdentifier()
    {
        return $this->projectIdentifier;
    }

    /**
     * @return string
     */
    public function getProjectApiKey()
    {
        return $this->projectApiKey;
    }

    /**
     * @return HttpClient
     */
    public function getHttpClient()
    {
        if ($this->httpClient === null) {
            $this->httpClient = new HttpClient(['base_uri' => self::BASE_URL]);
        }

        return $this->httpClient;
    }
}
