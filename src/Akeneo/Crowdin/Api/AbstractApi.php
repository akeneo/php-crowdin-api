<?php

namespace Akeneo\Crowdin\Api;

use Akeneo\Crowdin\Client;

/**
 * Abstract API
 *
 * @author Nicolas Dupont <nicolas@akeneo.com>
 */
abstract class AbstractApi implements ApiInterface
{
    /** @var Client */
    protected $client;

    /**
     * The method parameters
     *
     * @var array
     */
    protected $parameters = [];
    
    /**
     * The url parameters
     *
     * @var array
     */
    protected $urlParameters =  [];

    /**
     * Instantiate an API
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
    }
    
    /**
     * 
     * @param string $key
     * @param string $value
     * @return AbstractApi
     */
    public function addUrlParameter($key, $value)
    {
        $this->urlParameters[] = sprintf('%s=%s', $key, $value);
        
        return $this;
    }
    
    /**
     * 
     * @return string
     */
    protected function getUrlQueryString()
    {
        return implode('&', $this->urlParameters);
    }

    /**
     * {@inheritdoc}
     */
    abstract public function execute();
}
