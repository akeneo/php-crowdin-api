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
    protected $parameters = array();

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
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * {@inheritdoc}
     */
    abstract public function execute();
}
