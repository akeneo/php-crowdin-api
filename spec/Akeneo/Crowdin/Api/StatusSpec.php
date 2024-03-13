<?php

namespace spec\Akeneo\Crowdin\Api;

use Akeneo\Crowdin\Client;
use PhpSpec\ObjectBehavior;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class StatusSpec extends ObjectBehavior
{
    public function let(Client $client, HttpClientInterface $http, ResponseInterface $response)
    {
        $client->getHttpClient()->willReturn($http);
        $client->getProjectIdentifier()->willReturn('akeneo');
        $client->getProjectApiKey()->willReturn('1234');
        $http->request('GET', 'project/akeneo/status', ['headers' => ['Authorization' => 'Bearer 1234']])->willReturn($response);
        $response->getContent()->willReturn('<xml></xml>');
        $this->beConstructedWith($client);
    }

    public function it_should_be_an_api()
    {
        $this->shouldBeAnInstanceOf('Akeneo\Crowdin\Api\AbstractApi');
    }

    public function it_gets_project_translations_status()
    {
        $this->execute()->shouldBe('<xml></xml>');
    }
}
