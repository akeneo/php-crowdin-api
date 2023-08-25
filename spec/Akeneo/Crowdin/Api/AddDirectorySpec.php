<?php

namespace spec\Akeneo\Crowdin\Api;

use Akeneo\Crowdin\Api\AbstractApi;
use Akeneo\Crowdin\Client;
use PhpSpec\ObjectBehavior;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class AddDirectorySpec extends ObjectBehavior
{
    public function let(Client $client, HttpClientInterface $http)
    {
        $client->getHttpClient()->willReturn($http);
        $client->getProjectIdentifier()->willReturn('sylius');
        $client->getProjectApiKey()->willReturn('1234');
        $this->beConstructedWith($client);
    }

    public function it_should_be_an_api()
    {
        $this->shouldBeAnInstanceOf(AbstractApi::class);
    }

    public function it_should_not_add_with_no_directory(
        HttpClientInterface $http,
        ResponseInterface $response
    ) {
        $content = '<xml></xml>';
        $response->getContent()->willReturn($content);

        $http->request('POST', 'project/sylius/add-directory?key=1234')->willReturn($response);
        $this->shouldThrow()->duringExecute();
    }

    public function it_adds_a_directory(HttpClientInterface $http, ResponseInterface $response)
    {
        $this->setDirectory('directory-to-create');
        $content = '<?xml version="1.0" encoding="ISO-8859-1"?><success></success>';
        $response->getContent()->willReturn($content);
        $http->request(
            'POST',
            'project/sylius/add-directory?key=1234',
            ['body' => ['name' => 'directory-to-create']]
        )->willReturn($response);

        $this->execute()->shouldBe($content);
    }
}
