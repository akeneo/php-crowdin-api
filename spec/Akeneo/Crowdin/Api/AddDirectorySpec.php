<?php

namespace spec\Akeneo\Crowdin\Api;

use Akeneo\Crowdin\Client;
use Guzzle\Http\Client as HttpClient;
use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;
use PhpSpec\ObjectBehavior;

class AddDirectorySpec extends ObjectBehavior
{
    public function let(Client $client, HttpClient $http)
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

    public function it_should_not_add_with_no_directory(HttpClient $http, Request $request, Response $response)
    {
        $content = '<xml></xml>';
        $response->getBody(true)->willReturn($content);
        $request->send()->willReturn($response);

        $http->post('project/sylius/add-directory?key=1234')->willReturn($request);
        $this->shouldThrow('\InvalidArgumentException')->duringExecute();
    }

    public function it_adds_a_directory(HttpClient $http, Request $request, Response $response)
    {
        $this->setDirectory('directory-to-create');
        $content = '<?xml version="1.0" encoding="ISO-8859-1"?><success></success>';
        $response->getBody(true)->willReturn($content);
        $request->send()->willReturn($response);
        $http->post('project/sylius/add-directory?key=1234', [], ['name' => 'directory-to-create'])->willReturn($request);

        $this->execute()->shouldBe($content);
    }
}
