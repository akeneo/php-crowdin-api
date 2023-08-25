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
            "project/%s/language-status?key=%s",
            $this->client->getProjectIdentifier(),
            $this->client->getProjectApiKey()
        );
        $parameters = array_merge($this->parameters, ['form_params' => ['language' => $this->getLanguage()]]);
        $response = $this->client->getHttpClient()->request('POST', $path, $parameters);

        return $response->getContent();
    }
}
