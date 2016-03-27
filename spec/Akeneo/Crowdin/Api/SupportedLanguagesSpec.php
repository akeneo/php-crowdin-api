<?php

namespace spec\Akeneo\Crowdin\Api;

use Akeneo\Crowdin\Client;
use Guzzle\Http\Client as HttpClient;
use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SupportedLanguagesSpec extends ObjectBehavior
{
    public function let(Client $client, HttpClient $http, Request $request, Response $response)
    {
        $client->getHttpClient()->willReturn($http);
        $http->get('supported-languages')->willReturn($request);
        $request->send()->willReturn($response);
        $response->getBody(true)->willReturn('<xml></xml>');
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
