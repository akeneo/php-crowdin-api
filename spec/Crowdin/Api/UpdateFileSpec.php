<?php

namespace spec\Crowdin\Api;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Crowdin\Client;
use Guzzle\Http\Client as HttpClient;
use Guzzle\Http\Message\Response;
use Guzzle\Http\Message\Request;

class UpdateFileSpec extends ObjectBehavior
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
        $this->shouldBeAnInstanceOf('Crowdin\Api\AbstractApi');
    }

    function it_should_not_allow_not_existing_file()
    {
        $this->shouldThrow('\InvalidArgumentException')->duringAddTranslation('crowdin/path/file.yml', '/tmp/my-file.yml');
    }

    function it_has_files()
    {
        $this->addTranslation(__DIR__ . '/../../fixtures/messages.en.yml', 'crowdin/path/file.csv');
        $this->getTranslations()->shouldHaveCount(1);
    }

    function it_should_not_allow_update_with_no_file(HttpClient $http, Request $request, Response $response)
    {
        $content = '<xml></xml>';
        $response->getBody(true)->willReturn($content);
        $request->send()->willReturn($response);
        $http->post('project/akeneo/update-file?key=1234')->willReturn($request);
        $this->shouldThrow('\InvalidArgumentException')->duringExecute();
    }

    function it_updates_some_translation_files(HttpClient $http, Request $request, Response $response)
    {
        $this->addTranslation(__DIR__ . '/../../fixtures/messages.en.yml', 'crowdin/path/file.yml');
        $content = '<xml></xml>';
        $response->getBody(true)->willReturn($content);
        $request->send()->willReturn($response);
        $http->post(
            'project/akeneo/update-file?key=1234',
            array(),
            array("files[crowdin/path/file.yml]" => '@'.__DIR__ . '/../../fixtures/messages.en.yml')
        )->willReturn($request);
        $this->execute()->shouldBe($content);
    }

    function it_sends_additionnal_parameters(HttpClient $http, Request $request, Response $response)
    {
        $request->send()->willReturn($response);

        $http->post(Argument::any(), Argument::any(), array(
            "files[crowdin/path/file.yml]" => '@'.__DIR__ . '/../../fixtures/messages.en.yml',
            'foo' => 'bar',
        ))->shouldBeCalled()->willReturn($request);

        $this->addTranslation(__DIR__ . '/../../fixtures/messages.en.yml', 'crowdin/path/file.yml');
        $this->setParameters(array('foo' => 'bar'));
        $this->execute();
    }
}
