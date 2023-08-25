<?php

namespace spec\Akeneo\Crowdin\Api;

use Akeneo\Crowdin\Client;
use PhpSpec\ObjectBehavior;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class DownloadSpec extends ObjectBehavior
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

    public function it_downloads_all_translations(HttpClientInterface $http, ResponseInterface $response)
    {
        $this->setCopyDestination('/tmp');
        $this->setPackage('all.zip');
        $http->request('GET', 'project/akeneo/download/all.zip?key=1234', ["sink" => "/tmp/all.zip"])->willReturn(
            $response
        );
        $response->getContent()->willReturn('bin');
        $this->execute()->shouldBe('bin');
    }

    public function it_downloads_french_translations(HttpClientInterface $http, ResponseInterface $response)
    {
        $this->setCopyDestination('/tmp');
        $this->setPackage('fr.zip');
        $http->request('GET', 'project/akeneo/download/fr.zip?key=1234', ["sink" => "/tmp/fr.zip"])->willReturn(
            $response
        );
        $response->getContent()->willReturn('bin');
        $this->execute()->shouldBe('bin');
    }
}
