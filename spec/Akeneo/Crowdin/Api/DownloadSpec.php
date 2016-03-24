<?php

namespace spec\Akeneo\Crowdin\Api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Akeneo\Crowdin\Client;
use Guzzle\Http\Client as HttpClient;
use Guzzle\Http\Message\Response;
use Guzzle\Http\Message\Request;

class DownloadSpec extends ObjectBehavior
{
    function let(Client $client, HttpClient $http)
    {
        $client->getHttpClient()->willReturn($http);
        $client->getProjectIdentifier()->willReturn('akeneo');
        $client->getProjectApiKey()->willReturn('1234');
        $this->beConstructedWith($client);
    }

    function it_should_be_an_api()
    {
        $this->shouldBeAnInstanceOf('Akeneo\Crowdin\Api\AbstractApi');
    }

    function it_has_a_package_with_all_languages()
    {
        $this->setPackage('all.zip');
        $this->getPackage()->shouldReturn('all.zip');
    }

    function it_has_a_package_with_one_language()
    {
        $this->setPackage('fr.zip');
        $this->getPackage()->shouldReturn('fr.zip');
    }

    function it_has_a_copy_destination()
    {
        $this->setCopyDestination('/tmp/');
        $this->getCopyDestination()->shouldReturn('/tmp/');
    }

    function it_downloads_all_translations(HttpClient $http, Request $request, Response $response)
    {
        $this->setCopyDestination('/tmp');
        $this->setPackage('all.zip');
        $http->get('project/akeneo/download/all.zip?key=1234')->willReturn($request);
        $request->setResponseBody('/tmp/all.zip')->willReturn($request);
        $request->send()->willReturn($response);
        $response->getBody(true)->willReturn('bin');
        $this->execute()->shouldBe('bin');
    }

    function it_downloads_french_translations(HttpClient $http, Request $request, Response $response)
    {
        $this->setCopyDestination('/tmp');
        $this->setPackage('fr.zip');
        $http->get('project/akeneo/download/fr.zip?key=1234')->willReturn($request);
        $request->setResponseBody('/tmp/fr.zip')->willReturn($request);
        $request->send()->willReturn($response);
        $response->getBody(true)->willReturn('bin');
        $this->execute()->shouldBe('bin');
    }
}
