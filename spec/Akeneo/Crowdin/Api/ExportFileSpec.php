<?php

namespace spec\Akeneo\Crowdin\Api;

use Akeneo\Crowdin\Client;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ExportFileSpec extends ObjectBehavior
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

    public function it_retrieves_translation_file(HttpClient $http, Request $request, Response $response)
    {
        $content = 'file-content';
        $response->getBody()->willReturn($content);
        $http->get('project/akeneo/export-file?key=1234')->willReturn($response);
        $this->execute()->shouldBe($content);
    }
}
