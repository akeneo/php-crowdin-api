<?php

namespace spec\Crowdin\Api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Crowdin\Client;
use Guzzle\Http\Client as HttpClient;
use Guzzle\Http\Message\Response;
use Guzzle\Http\Message\Request;

class DownloadSpec extends ObjectBehavior
{
    function let(Client $client, HttpClient $http, Request $request, Response $response)
    {
        $client->getHttpClient()->willReturn($http);
        $client->getProjectIdentifier()->willReturn('akeneo');
        $client->getProjectApiKey()->willReturn('1234');
        $http->get('project/akeneo/download/all.zip?key=1234')->willReturn($request);
        $request->setResponseBody('/tmp/all.zip')->willReturn($request);
        $request->send()->willReturn($response);
        $response->getBody(true)->willReturn('bin');
        $this->beConstructedWith($client);
    }

    function it_should_be_an_api()
    {
        $this->shouldBeAnInstanceOf('Crowdin\Api\AbstractApi');
    }

    function it_has_an_package_with_all_languages()
    {
        $this->setPackage('all.zip');
        $this->getPackage()->shouldReturn('all.zip');
    }

    function it_has_an_package_with_one_languages()
    {
        $this->setPackage('fr.zip');
        $this->getPackage()->shouldReturn('fr.zip');
    }

    function it_has_a_copy_destination()
    {
        $this->setCopyDestination('/tmp/');
        $this->getCopyDestination()->shouldReturn('/tmp/');
    }

    function it_download_all_translations()
    {
        $this->setCopyDestination('/tmp');
        $this->setPackage('all.zip');
        $this->execute()->shouldBe('bin');
    }
}
