<?php

namespace spec\Akeneo\Crowdin\Api;

use Akeneo\Crowdin\Client;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DownloadSpec extends ObjectBehavior
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

    public function it_has_a_package_with_all_languages()
    {
        $this->setPackage('all.zip');
        $this->getPackage()->shouldReturn('all.zip');
    }

    public function it_has_a_package_with_one_language()
    {
        $this->setPackage('fr.zip');
        $this->getPackage()->shouldReturn('fr.zip');
    }

    public function it_has_a_copy_destination()
    {
        $this->setCopyDestination('/tmp/');
        $this->getCopyDestination()->shouldReturn('/tmp/');
    }

    public function it_downloads_all_translations(HttpClient $http, Request $request, Response $response)
    {
        $this->setCopyDestination('/tmp');
        $this->setPackage('all.zip');
        $http->get('project/akeneo/download/all.zip?key=1234')->willReturn($response);
        $response->getBody()->willReturn('bin');
        $this->execute()->shouldBe('bin');
    }

    public function it_downloads_french_translations(HttpClient $http, Request $request, Response $response)
    {
        $this->setCopyDestination('/tmp');
        $this->setPackage('fr.zip');
        $http->get('project/akeneo/download/fr.zip?key=1234')->willReturn($response);
        $response->getBody()->willReturn('bin');
        $this->execute()->shouldBe('bin');
    }
}
