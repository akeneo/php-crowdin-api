<?php

namespace spec\Crowdin\Api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Crowdin\Client;
use Guzzle\Http\Client as HttpClient;
use Guzzle\Http\Message\Response;
use Guzzle\Http\Message\Request;

class StatusSpec extends ObjectBehavior
{
    function let(Client $client, HttpClient $http, Request $request, Response $response)
    {
        $client->getHttpClient()->willReturn($http);
        $client->getProjectIdentifier()->willReturn('akeneo');
        $client->getProjectApiKey()->willReturn('1234');
        $http->get('project/akeneo/status?key=1234')->willReturn($request);
        $request->send()->willReturn($response);
        $response->getBody(true)->willReturn('<xml></xml>');
        $this->beConstructedWith($client);
    }

    function it_should_be_an_api()
    {
        $this->shouldBeAnInstanceOf('Crowdin\Api\AbstractApi');
    }

    function it_gets_project_translations_status()
    {
        $this->execute()->shouldBe('<xml></xml>');
    }
}
