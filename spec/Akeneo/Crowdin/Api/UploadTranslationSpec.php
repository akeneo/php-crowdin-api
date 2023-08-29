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

class UploadTranslationSpec extends ObjectBehavior
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
        $this->shouldBeAnInstanceOf('Akeneo\Crowdin\Api\AbstractApi');
    }

    public function it_should_not_allow_not_existing_translation()
    {
        $this->shouldThrow('\InvalidArgumentException')->duringAddTranslation(
            'crowdin/path/file.yml',
            '/tmp/my-file.yml'
        );
    }

    public function it_has_translations()
    {
        $this->addTranslation('spec/fixtures/messages.en.yml', 'crowdin/path/file.csv');
        $this->getTranslations()->shouldHaveCount(1);
    }

    public function it_does_not_import_duplicates_by_default()
    {
        $this->areDuplicatesImported()->shouldBe(false);
    }

    public function it_does_not_import_equal_suggestions_by_default()
    {
        $this->areEqualSuggestionsImported()->shouldBe(false);
    }

    public function it_does_not_auto_approve_imported_by_default()
    {
        $this->areImportsAutoApproved()->shouldBe(false);
    }

    public function it_should_not_allow_upload_with_no_translation(
        HttpClientInterface $http,
        ResponseInterface   $response
    ) {
        $this->setLocale('fr');
        $content = '<xml></xml>';
        $response->getContent()->willReturn($content);
        $http->request('POST', 'project/sylius/upload-translation?key=1234')->willReturn($response);

        $this->shouldThrow('\InvalidArgumentException')->duringExecute();
    }

    public function it_should_not_allow_upload_with_no_locale(HttpClientInterface $http, ResponseInterface $response)
    {
        $this->addTranslation('spec/fixtures/messages.en.yml', 'crowdin/path/file.yml');
        $content = '<xml></xml>';
        $response->getContent()->willReturn($content);
        $http->request('POST', 'project/sylius/upload-translation?key=1234')->willReturn($response);

        $this->shouldThrow()->duringExecute();
    }

    public function it_uploads_some_translations(
        FileReader          $fileReader,
        HttpClientInterface $http,
        ResponseInterface   $response
    ) {
        $this->addTranslation('spec/fixtures/messages.en.yml', 'crowdin/path/file.yml');
        $this->setLocale('fr');
        $content = '<xml></xml>';
        $response->getContent()->willReturn($content);
        $fakeResource = '[fake resource]';
        $fileReader->readTranslation(Argument::any())->willReturn($fakeResource);
        $http->request(
            'POST',
            'project/sylius/upload-translation?key=1234',
            [
                'headers' => [
                    'Content-Type' => 'multipart/form-data'
                ],
                'body' => [
                    'import_duplicates' => 0,
                    'import_eq_suggestions' => 0,
                    'auto_approve_imported' => 0,
                    'language' => 'fr',
                    'files[crowdin/path/file.yml]' => $fakeResource,
                ],
            ]
        )->willReturn($response);
        $this->execute()->shouldBe($content);
    }
}
