<?php

namespace Crowdin;

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
            default:
                throw new \InvalidArgumentException(sprintf('Undefined api method "%s"', $method));
        }

        return $api;
    }

    public function getProjectIdentifier()
    {
        return $this->projectIdentifier;
    }

    public function getProjectApiKey()
    {
        return $this->projectApiKey;
    }
}
