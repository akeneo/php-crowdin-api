<?php

namespace spec\Akeneo\Crowdin\Api;

use Akeneo\Crowdin\Client;
use PhpSpec\ObjectBehavior;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class LanguageStatusSpec extends ObjectBehavior
{
    public function let(Client $client, HttpClientInterface $http)
    {
        $client->getHttpClient()->willReturn($http);
        $client->getProjectIdentifier()->willReturn('akeneo');
        $client->getProjectApiKey()->willReturn('1234');
        $this->beConstructedWith($client);
    }

    public function it_should_be_an_api()
    {
        $this->shouldBeAnInstanceOf('Akeneo\Crowdin\Api\AbstractApi');
    }

    public function it_gets_project_language_status(
        HttpClientInterface $http,
        ResponseInterface $response
    ) {
        $this->setLanguage('fr')->shouldBe($this);
        $http->request(
            'POST',
            'project/akeneo/language-status?key=1234',
            ['form_params' => ['language' => 'fr']]
        )->willReturn($response);
        $response->getContent()->willReturn('<xml></xml>');
        $this->execute()->shouldBe('<xml></xml>');
    }
}
