<?php

namespace spec\Akeneo\Crowdin\Api;

use Akeneo\Crowdin\Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ChangeDirectorySpec extends ObjectBehavior
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

    public function it_should_have_a_name()
    {
        $this->shouldThrow()->during('execute', []);
    }

    public function it_should_set_name(
        HttpClientInterface $http,
        ResponseInterface $response
    ) {
        $this->setName('myname');
        $path = 'project/sylius/change-directory';
        $data = [
            'headers' => ['authorization' => 'Bearer 1234'],
            'body' => ['name' => 'myname']
        ];
        $http->request('POST', $path, $data)->willReturn($response);
        $response->getContent(Argument::any())->willReturn('content');

        $this->execute()->shouldReturn('content');
    }

    public function it_should_set_data(
        HttpClientInterface $http,
        ResponseInterface $response
    ) {
        $this->setName('myName');
        $this->setBranch('myBranch');
        $this->setExportPattern('myExportPattern');
        $this->setTitle('myTitle');
        $this->setNewName('myNewName');
        $path = 'project/sylius/change-directory';
        $data = [
            'headers' => ['authorization' => 'Bearer 1234'],
            'body' => [
                'name' => 'myName',
                'branch' => 'myBranch',
                'export_pattern' => 'myExportPattern',
                'title' => 'myTitle',
                'new_name' => 'myNewName',
            ],
        ];
        $http->request('POST', $path, $data)->willReturn($response);
        $response->getContent()->willReturn('content');

        $this->execute()->shouldReturn('content');
    }
}
