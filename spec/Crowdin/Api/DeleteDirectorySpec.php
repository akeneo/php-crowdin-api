<?php

namespace spec\Crowdin\Api;

use Crowdin\Client;
use Guzzle\Http\Client as HttpClient;
use Guzzle\Http\Message\Response;
use Guzzle\Http\Message\Request;
use PhpSpec\ObjectBehavior;

class DeleteDirectorySpec extends ObjectBehavior
{
    function let(Client $client, HttpClient $http)
    {
        $client->getHttpClient()->willReturn($http);
        $client->getProjectIdentifier()->willReturn('sylius');
        $client->getProjectApiKey()->willReturn('1234');
        $this->beConstructedWith($client);
    }

    function it_should_be_an_api()
    {
        $this->shouldBeAnInstanceOf('Crowdin\Api\AbstractApi');
    }

    function it_should_not_delete_with_no_directory(HttpClient $http, Request $request, Response $response)
    {
        $content = '<xml></xml>';
        $response->getBody(true)->willReturn($content);
        $request->send()->willReturn($response);

        $http->post('project/sylius/delete-directory?key=1234')->willReturn($request);
        $this->shouldThrow('\InvalidArgumentException')->duringExecute();
    }

    function it_deletes_a_directory(HttpClient $http, Request $request, Response $response)
    {
        $this->setDirectory('directory-to-delete');
        $content = '<?xml version="1.0" encoding="ISO-8859-1"?><success></success>';
        $response->getBody(true)->willReturn($content);
        $request->send()->willReturn($response);
        $http->post('project/sylius/delete-directory?key=1234', array(), array('name' => 'directory-to-delete'))->willReturn($request);

        $this->execute()->shouldBe($content);
    }
} 