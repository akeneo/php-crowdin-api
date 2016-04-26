<?php

namespace spec\Akeneo\Crowdin\Api;

use Akeneo\Crowdin\Client;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UpdateFileSpec extends ObjectBehavior
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

    public function it_should_not_allow_not_existing_file()
    {
        $this->shouldThrow('\InvalidArgumentException')->duringAddTranslation('crowdin/path/file.yml', '/tmp/my-file.yml');
    }

    public function it_has_files()
    {
        $this->addTranslation(__DIR__ . '/../../../fixtures/messages.en.yml', 'crowdin/path/file.csv');
        $this->getTranslations()->shouldHaveCount(1);
    }

    public function it_should_not_allow_update_with_no_file(HttpClient $http, Request $request, Response $response)
    {
        $content = '<xml></xml>';
        $response->getBody(true)->willReturn($content);
        $http->post('project/akeneo/update-file?key=1234')->willReturn($response);
        $this->shouldThrow('\InvalidArgumentException')->duringExecute();
    }

    public function it_updates_some_translation_files(HttpClient $http, Request $request, Response $response)
    {
        $this->addTranslation(__DIR__ . '/../../../fixtures/messages.en.yml', 'crowdin/path/file.yml');
        $content = '<xml></xml>';
        $response->getBody()->willReturn($content);
        $http->post(
            'project/akeneo/update-file?key=1234',
            array('multipart' => array(
                array(
                    'name' => "files[crowdin/path/file.yml]",
                    'contents' => '@'.__DIR__ . '/../../../fixtures/messages.en.yml',
                )
            ))
        )->willReturn($response);
        $this->execute()->shouldBe($content);
    }

    public function it_sends_additionnal_parameters(HttpClient $http, Request $request, Response $response)
    {
        $http->post(
            Argument::any(),
            array('multipart' => array(
                    'foo' => 'bar',
                    array(
                        'name' => "files[crowdin/path/file.yml]",
                        'contents' => '@'.__DIR__ . '/../../../fixtures/messages.en.yml',
                    )
                )
            )
        )->shouldBeCalled()->willReturn($response);

        $this->addTranslation(__DIR__ . '/../../../fixtures/messages.en.yml', 'crowdin/path/file.yml');
        $this->setParameters(['foo' => 'bar']);
        $this->execute();
    }
}
