<?php

namespace spec\Akeneo\Crowdin\Api;

use Akeneo\Crowdin\Client;
use Akeneo\Crowdin\FileReader;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class UpdateFileSpec extends ObjectBehavior
{
    public function let(Client $client, HttpClientInterface $http, FileReader $fileReader)
    {
        $client->getHttpClient()->willReturn($http);
        $client->getProjectIdentifier()->willReturn('akeneo');
        $client->getProjectApiKey()->willReturn('1234');
        $this->beConstructedWith($client, $fileReader);
    }

    public function it_should_be_an_api()
    {
        $this->shouldBeAnInstanceOf('Akeneo\Crowdin\Api\AbstractApi');
    }

    public function it_should_not_allow_not_existing_file()
    {
        $this->shouldThrow('\InvalidArgumentException')->duringAddTranslation(
            'crowdin/path/file.yml',
            '/tmp/my-file.yml'
        );
    }

    public function it_has_files()
    {
        $this->addTranslation(__DIR__ . '/../../../fixtures/messages.en.yml', 'crowdin/path/file.csv');
        $this->getTranslations()->shouldHaveCount(1);
    }

    public function it_should_not_allow_update_with_no_file(HttpClientInterface $http, ResponseInterface $response)
    {
        $content = '<xml></xml>';
        $response->getContent(true)->willReturn($content);
        $http->request('POST', 'project/akeneo/update-file?key=1234')->willReturn($response);
        $this->shouldThrow()->duringExecute();
    }

    public function it_updates_some_translation_files(
        $fileReader,
        HttpClientInterface $http,
        ResponseInterface $response
    ) {
        $localPath = __DIR__ . '/../../../fixtures/messages.en.yml';
        $this->addTranslation($localPath, 'path/to/crowdin.yml');
        $content = '<xml></xml>';
        $response->getContent()->willReturn($content);
        $fakeResource = '[fake resource]';
        $fileReader->readTranslation(Argument::any())->willReturn($fakeResource);
        $http->request(
            'POST',
            'project/akeneo/update-file?key=1234',
            [
                'multipart' => [
                    [
                        'name' => "files[path/to/crowdin.yml]",
                        'contents' => $fakeResource,
                    ],
                ],
            ]
        )->willReturn($response);
        $this->execute()->shouldBe($content);
    }

    public function it_sends_additionnal_parameters(
        FileReader $fileReader,
        HttpClientInterface $http,
        ResponseInterface $response
    ) {
        $fakeResource = '[fake resource]';
        $fileReader->readTranslation(Argument::any())->willReturn($fakeResource);

        $http->request(
            'POST',
            Argument::any(),
            [
                'multipart' => [
                    'foo' => 'bar',
                    [
                        'name' => "files[path/to/crowdin.yml]",
                        'contents' => $fakeResource,
                    ],
                ],
            ]
        )->shouldBeCalled()->willReturn($response);
        $response->getContent()->willReturn('content');

        $this->addTranslation(__DIR__ . '/../../../fixtures/messages.en.yml', 'path/to/crowdin.yml');
        $this->setParameters(['foo' => 'bar']);
        $this->execute();
    }
}
