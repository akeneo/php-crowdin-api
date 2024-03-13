<?php

namespace spec\Akeneo\Crowdin\Api;

use Akeneo\Crowdin\Client;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class SupportedLanguagesSpec extends ObjectBehavior
{
    public function let(Client $client, HttpClientInterface $http, ResponseInterface $response)
    {
        $client->getHttpClient()->willReturn($http);
        $client->getProjectApiKey()->willReturn('1234');
        $http->request('GET', 'supported-languages', ['headers' => ['authorization' => 'Bearer 1234']])->willReturn($response);
        $response->getContent()->willReturn('<xml></xml>');
        $this->beConstructedWith($client);
    }

    public function it_should_be_an_api()
    {
        $this->shouldBeAnInstanceOf('Akeneo\Crowdin\Api\AbstractApi');
    }

    public function it_gets_supported_languages()
    {
        $this->execute()->shouldBe('<xml></xml>');
    }
}
