<?php

namespace Crowdin\Api;

use Crowdin\Client;

/**
 * Abstract API
 *
 * @author Nicolas Dupont <nicolas@akeneo.com>
 */
abstract class AbstractApi implements ApiInterface
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * The method parameters
     *
     * @var array
     */
    protected $parameters;

    /**
     * Instanciat an API
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
    abstract public  function execute();

    /**
     * {@inheritdoc}
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function getExpectedParameters()
    {
        return array();
    }
}
