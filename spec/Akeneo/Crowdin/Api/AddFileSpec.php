<?php

namespace spec\Akeneo\Crowdin\Api;

use Akeneo\Crowdin\Client;
use Guzzle\Http\Client as HttpClient;
use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;
use PhpSpec\ObjectBehavior;

class AddFileSpec extends ObjectBehavior
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

    public function it_should_not_allow_not_existing_file()
    {
        $this->shouldThrow('\InvalidArgumentException')->duringAddTranslation('crowdin/path/file.yml', '/tmp/my-file.yml');
    }

    public function it_has_files()
    {
        $this->addTranslation(__DIR__ . '/../../../fixtures/messages.en.yml', 'crowdin/path/file.csv');
        $this->getTranslations()->shouldHaveCount(1);
    }

    public function it_should_not_add_with_no_file(HttpClient $http, Request $request, Response $response)
    {
        $content = '<xml></xml>';
        $response->getBody(true)->willReturn($content);
        $request->send()->willReturn($response);

        $http->post('project/sylius/add-file?key=1234')->willReturn($request);
        $this->shouldThrow('\InvalidArgumentException')->duringExecute();
    }

    public function it_adds_a_file(HttpClient $http, Request $request, Response $response)
    {
        $this->addTranslation(__DIR__ . '/../../../fixtures/messages.en.yml', 'path/to/crowdin.yml');
        $content = '<?xml version="1.0" encoding="ISO-8859-1"?><success></success>';
        $response->getBody(true)->willReturn($content);
        $request->send()->willReturn($response);
        $http->post(
            'project/sylius/add-file?key=1234',
            [],
            ['files[path/to/crowdin.yml]' => '@' . __DIR__ . '/../../../fixtures/messages.en.yml']
        )->willReturn($request);

        $this->execute()->shouldBe($content);
    }
}
