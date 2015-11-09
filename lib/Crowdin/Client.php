<?php

namespace Crowdin;

use Guzzle\Http\Client as HttpClient;

/**
 * Simple Crowdin PHP client
 *
 * @author Nicolas Dupont <nicolas@akeneo.com>
 * @author Aaron Scherer <aequasi@gmail.com>
 */
class Client
{
    /**
     * @var string base url
     */
    const BASE_URL = 'http://api.crowdin.net/api';

    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @type Config The configuration of the Crowdin Client
     */
    private $config;

    /**
     * Instantiates a new Crowdin Client
     *
     * @param array $data Should at least contain 'identifier', and 'apiKey'. Can also contain 'format'
     */
    public function __construct(array $data)
    {
        $this->config = new Config($data);
    }

    /**
     * @param string $method the api method
     *
     * @throws \InvalidArgumentException
     *
     * @return mixed
     */
    public function api($method)
    {
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
                $api = new Api\AddFile($this);
                break;
            case 'update-file':
                $api = new Api\UpdateFile($this);
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
                $api = new Api\UploadTranslation($this);
                break;
            default:
                throw new \InvalidArgumentException(sprintf('Undefined api method "%s"', $method));
        }

        return $api;
    }

    /**
     * Returns the Config object for the Crowdin Client
     *
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Returns the clients project identifier
     *
     * @return string
     */
    public function getProjectIdentifier()
    {
        return $this->config->get('identifier');
    }

    /**
     * Returns the client's api key
     *
     * @return string
     */
    public function getProjectApiKey()
    {
        return $this->config->get('apiKey');
    }

    /**
     * Returns the client's desired response format
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->config->get('format');
    }

    /**
     * Returns the Guzzle HttpClient
     *
     * @return HttpClient
     */
    public function getHttpClient()
    {
        if ($this->httpClient === null) {
            $this->httpClient = new HttpClient(self::BASE_URL);
        }

        return $this->httpClient;
    }
}
