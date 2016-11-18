<?php

namespace spec\Akeneo\Crowdin\Api;

use Akeneo\Crowdin\Client;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\Response;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LanguageStatusSpec extends ObjectBehavior
{
    public function let(Client $client, HttpClient $http)
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
        HttpClient $http,
        Response $response
    ) {
        $this->setLanguage('fr')->shouldBe($this);
        $http->post('project/akeneo/language-status?key=1234', ['form_params' => ['language' => 'fr']])->willReturn($response);
        $response->getBody(true)->willReturn('<xml></xml>');
        $this->execute()->shouldBe('<xml></xml>');
    }
}
