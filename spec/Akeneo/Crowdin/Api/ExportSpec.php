<?php

namespace spec\Akeneo\Crowdin\Api;

use Akeneo\Crowdin\Client;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ExportSpec extends ObjectBehavior
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

    public function it_builds_last_translations(HttpClient $http, Request $request, Response $response)
    {
        $content = '<?xml version="1.0" encoding="ISO-8859-1"?><success status="built"></success>';
        $response->getBody()->willReturn($content);
        $http->get('project/akeneo/export?key=1234')->willReturn($response);
        $this->execute()->shouldBe($content);
    }

    public function it_skips_build_if_less_than_half_an_hour(HttpClient $http, Request $request, Response $response)
    {
        $content = '<?xml version="1.0" encoding="ISO-8859-1"?><success status="skipped"></success>';
        $response->getBody()->willReturn($content);
        $http->get('project/akeneo/export?key=1234')->willReturn($response);
        $this->execute()->shouldBe($content);
    }
}
