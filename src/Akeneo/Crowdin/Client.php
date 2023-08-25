<?php

namespace Akeneo\Crowdin;

use InvalidArgumentException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Simple Crowdin PHP client
 *
 * @author Nicolas Dupont <nicolas@akeneo.com>
 */
class Client
{
    /** @var string base url */
    const BASE_URL = 'https://api.crowdin.com/api/';

    protected ?HttpClientInterface $httpClient = null;

    public function __construct(protected string $projectIdentifier, protected string $projectApiKey)
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function api(string $method): object
    {
        $fileReader = new FileReader();

        return match ($method) {
            'info' => new Api\Info($this),
            'supported-languages' => new Api\SupportedLanguages($this),
            'status' => new Api\Status($this),
            'download' => new Api\Download($this),
            'add-file' => new Api\AddFile($this, $fileReader),
            'update-file' => new Api\UpdateFile($this, $fileReader),
            'delete-file' => new Api\DeleteFile($this),
            'export' => new Api\Export($this),
            'add-directory' => new Api\AddDirectory($this),
            'delete-directory' => new Api\DeleteDirectory($this),
            'upload-translation' => new Api\UploadTranslation($this, $fileReader),
            'language-status' => new Api\LanguageStatus($this),
            default => throw new InvalidArgumentException(sprintf('Undefined api method "%s"', $method)),
        };
    }

    public function getProjectIdentifier(): string
    {
        return $this->projectIdentifier;
    }

    public function getProjectApiKey(): string
    {
        return $this->projectApiKey;
    }

    public function getHttpClient(): HttpClientInterface
    {
        if (null === $this->httpClient) {
            $this->httpClient = HttpClient::createForBaseUri(self::BASE_URL);
        }

        return $this->httpClient;
    }
}
