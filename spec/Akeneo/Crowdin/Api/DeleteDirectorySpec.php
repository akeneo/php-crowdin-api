<?php

namespace spec\Akeneo\Crowdin\Api;

use Akeneo\Crowdin\Client;
use PhpSpec\ObjectBehavior;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class DeleteDirectorySpec extends ObjectBehavior
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
        $this->shouldBeAnInstanceOf('Akeneo\Crowdin\Api\AbstractApi');
    }

    public function it_should_not_delete_with_no_directory(HttpClientInterface $http, ResponseInterface $response)
    {
        $content = '<xml></xml>';
        $response->getContent()->willReturn($content);

        $http->request('POST', 'project/sylius/delete-directory?key=1234')->willReturn($response);
        $this->shouldThrow()->duringExecute();
    }

    public function it_deletes_a_directory(HttpClientInterface $http, ResponseInterface $response)
    {
        $this->setDirectory('directory-to-delete');
        $content = '<?xml version="1.0" encoding="ISO-8859-1"?><success></success>';
        $response->getContent()->willReturn($content);
        $http->request(
            'POST',
            'project/sylius/delete-directory?key=1234',
            ['form_params' => ['name' => 'directory-to-delete']]
        )->willReturn($response);

        $this->execute()->shouldBe($content);
    }
}
