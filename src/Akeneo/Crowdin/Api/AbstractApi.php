<?php

namespace Akeneo\Crowdin\Api;

use Akeneo\Crowdin\Client;

/**
 * @author Nicolas Dupont <nicolas@akeneo.com>
 */
abstract class AbstractApi implements ApiInterface
{
    protected array $parameters = [];
    protected array $urlParameters =  [];

    public function __construct(protected Client $client)
    {
    }

    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }

    public function addUrlParameter(string $key, string $value): static
    {
        $this->urlParameters[$key] = $value;

        return $this;
    }

    protected function getUrlQueryString(): string
    {
        return http_build_query($this->urlParameters);
    }

    abstract public function execute();
}
