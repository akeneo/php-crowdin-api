<?php

namespace spec\Akeneo\Crowdin\Api;

use Akeneo\Crowdin\Api\AbstractApi;
use Akeneo\Crowdin\Client;
use Akeneo\Crowdin\FileReader;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class AddFileSpec extends ObjectBehavior
{
    public function let(Client $client, HttpClientInterface $http, FileReader $fileReader)
    {
        $client->getHttpClient()->willReturn($http);
        $client->getProjectIdentifier()->willReturn('sylius');
        $client->getProjectApiKey()->willReturn('1234');
        $this->beConstructedWith($client, $fileReader);
    }

    public function it_should_be_an_api()
    {
        $this->shouldBeAnInstanceOf(AbstractApi::class);
    }

    public function it_should_not_allow_not_existing_file()
    {
        $this->shouldThrow(\InvalidArgumentException::class)->duringAddTranslation(
            'crowdin/path/file.yml',
            '/tmp/my-file.yml'
        );
    }

    public function it_has_files()
    {
        $this->addTranslation(__DIR__ . '/../../../fixtures/messages.en.yml', 'crowdin/path/file.csv');
        $this->getTranslations()->shouldHaveCount(1);
    }

    public function it_should_not_add_with_no_file(HttpClientInterface $http, ResponseInterface $response)
    {
        $content = '<xml></xml>';
        $response->getContent()->willReturn($content);

        $http->request(
            'POST',
            'project/sylius/add-file',
            ['headers' => ['Authorization' => 'Bearer 1234']]
        )->willReturn($response);
        $this->shouldThrow('\InvalidArgumentException')->duringExecute();
    }

    public function it_adds_a_file(FileReader $fileReader, HttpClientInterface $http, ResponseInterface $response)
    {
        $localPath = __DIR__ . '/../../../fixtures/messages.en.yml';
        $this->addTranslation($localPath, 'path/to/crowdin.yml');
        $content = '<?xml version="1.0" encoding="ISO-8859-1"?><success></success>';
        $response->getContent()->willReturn($content);
        $fakeResource = '[fake resource]';
        $fileReader->readTranslation(Argument::any())->willReturn($fakeResource);
        $http->request(
            'POST',
            'project/sylius/add-file',
            [
                'headers' => [
                    'Content-Type' => 'multipart/form-data',
                    'Authorization' => 'Bearer 1234'
                ],
                'body' => [
                    'files[path/to/crowdin.yml]' => $fakeResource,
                ],
            ]
        )->shouldBeCalled()->willReturn($response);

        $this->execute()->shouldBe($content);
    }
}
