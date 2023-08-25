<?php

namespace spec\Akeneo\Crowdin\Api;

use Akeneo\Crowdin\Api\AbstractApi;
use Akeneo\Crowdin\Client;
use PhpSpec\ObjectBehavior;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class InfoSpec extends ObjectBehavior
{
    public function let(Client $client, HttpClientInterface $http, ResponseInterface $response)
    {
        $client->getHttpClient()->willReturn($http);
        $client->getProjectIdentifier()->willReturn('akeneo');
        $client->getProjectApiKey()->willReturn('1234');
        $http->request('GET', 'project/akeneo/info?key=1234')->willReturn($response);
        $response->getContent()->willReturn('<xml></xml>');
        $this->beConstructedWith($client);
    }

    public function it_should_be_an_api()
    {
        $this->shouldBeAnInstanceOf(AbstractApi::class);
    }

    public function it_gets_project_info()
    {
        $this->execute()->shouldBe('<xml></xml>');
    }
}
