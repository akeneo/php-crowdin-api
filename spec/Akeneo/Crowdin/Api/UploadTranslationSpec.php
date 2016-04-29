<?php

namespace spec\Akeneo\Crowdin\Api;

use Akeneo\Crowdin\Client;
use Akeneo\Crowdin\FileReader;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UploadTranslationSpec extends ObjectBehavior
{
    public function let(Client $client, HttpClient $http, FileReader $fileReader)
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
        $this->shouldThrow('\InvalidArgumentException')->duringAddTranslation('crowdin/path/file.yml', '/tmp/my-file.yml');
    }

    public function it_has_translations()
    {
        $this->addTranslation('crowdin/path/file.csv',  'spec/fixtures/messages.en.yml');
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

    public function it_should_not_allow_upload_with_no_translation(HttpClient $http, Request $request, Response $response)
    {
        $this->setLocale('fr');
        $content = '<xml></xml>';
        $response->getBody()->willReturn($content);
        $http->post('project/sylius/upload-translation?key=1234')->willReturn($response);

        $this->shouldThrow('\InvalidArgumentException')->duringExecute();
    }

    public function it_should_not_allow_upload_with_no_locale(HttpClient $http, Request $request, Response $response)
    {
        $this->addTranslation('crowdin/path/file.yml',  'spec/fixtures/messages.en.yml');
        $content = '<xml></xml>';
        $response->getBody()->willReturn($content);
        $http->post('project/sylius/upload-translation?key=1234')->willReturn($response);

        $this->shouldThrow('\InvalidArgumentException')->duringExecute();
    }

    public function it_uploads_some_translations(FileReader $fileReader, HttpClient $http, Request $request, Response $response)
    {
        $localPath = __DIR__ . '/../../../fixtures/messages.en.yml';
        $this->addTranslation($localPath, 'spec/fixtures/messages.en.yml');
        $this->setLocale('fr');
        $content = '<xml></xml>';
        $response->getBody()->willReturn($content);
        $fakeResource = '[fake resource]';
        $fileReader->readStream(Argument::any())->willReturn($fakeResource);
        $http->post(
            'project/sylius/upload-translation?key=1234',
            ['multipart' => [
                [
                    'name'      => 'import_duplicates',
                    'contents'  => 0
                ],
                [
                    'name'      => 'import_eq_suggestions',
                    'contents'  => 0
                ],
                [
                    'name'      => 'auto_approve_imported',
                    'contents'  => 0
                ],
                [
                    'name'      => 'language',
                    'contents'  => 'fr'
                ],
                [
                    'name'      => 'files['.$localPath.']',
                    'contents'  => $fakeResource,
                ],
            ]]
        )->willReturn($response);
        $this->execute()->shouldBe($content);
    }
}
