<?php

namespace Akeneo\Crowdin\Api;

/**
 * Project translation progress by language
 *
 * @author Pierre Allard <pierre.allard@akeneo.com>
 * @see    http://crowdin.net/page/api/language-status
 */
class LanguageStatus extends AbstractApi
{
    protected string $language;

    public function setLanguage(string $language): static
    {
        $this->language = $language;

        return $this;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function execute()
    {
        $path = sprintf(
            "project/%s/language-status",
            $this->client->getProjectIdentifier()
        );
        $parameters = array_merge(
            $this->parameters,
            [
                'headers' => ['Authorization' => 'Bearer ' . $this->client->getProjectApiKey()],
                'body' => ['language' => $this->getLanguage()]
            ]
        );
        $response = $this->client->getHttpClient()->request('POST', $path, $parameters);

        return $response->getContent();
    }
}
