<?php

namespace Crowdin;

use Guzzle\Http\Client as HttpClient;

/**
 * Simple Crowdin PHP client
 *
 * @author Nicolas Dupont <nicolas@akeneo.com>
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
    protected $httpClient;

    /**
     * @var string the project identifier
     */
    protected $projectIdentifier;

    /**
     * @var string the project api key
     */
    protected $projectApiKey;

    /**
     * Instanciate a new Crowdin Client
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
     * @param string $method     the api method
     * @param array  $parameters the method parameters
     *
     * @throws InvalidArgumentException
     *
     * @return mixed
     */
    public function api($method, $parameters = array())
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
            default:
                throw new \InvalidArgumentException(sprintf('Undefined api method "%s"', $method));
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
            $this->httpClient = new HttpClient(self::BASE_URL);
        }

        return $this->httpClient;
    }
}
