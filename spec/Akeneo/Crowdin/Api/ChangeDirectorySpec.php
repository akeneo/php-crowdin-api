<?php

namespace spec\Akeneo\Crowdin\Api;

use Akeneo\Crowdin\Client;
use Guzzle\Http\Client as HttpClient;
use Guzzle\Http\EntityBodyInterface;
use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ChangeDirectorySpec extends ObjectBehavior
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

    public function it_should_have_a_name()
    {
        $this->shouldThrow('\InvalidArgumentException')->during('execute', []);
    }

    public function it_should_set_name(
        $http,
        Request $request,
        Response $response,
        EntityBodyInterface $body
    ) {
        $this->setName('myname');
        $path = 'project/sylius/change-directory?key=1234';
        $data = ['name' => 'myname'];
        $http->post($path, [], $data)->willReturn($request);
        $request->send()->willReturn($response);
        $response->getBody(Argument::any())->willReturn($body);

        $this->execute()->shouldReturn($body);
    }

    public function it_should_set_data(
        $http,
        Request $request,
        Response $response,
        EntityBodyInterface $body
    ) {
        $this->setName('myName');
        $this->setBranch('myBranch');
        $this->setExportPattern('myExportPattern');
        $this->setTitle('myTitle');
        $this->setNewName('myNewName');
        $path = 'project/sylius/change-directory?key=1234';
        $data = [
            'name'           => 'myName',
            'branch'         => 'myBranch',
            'export_pattern' => 'myExportPattern',
            'title'          => 'myTitle',
            'new_name'       => 'myNewName'
        ];
        $http->post($path, [], $data)->willReturn($request);
        $request->send()->willReturn($response);
        $response->getBody(Argument::any())->willReturn($body);

        $this->execute()->shouldReturn($body);
    }
}
