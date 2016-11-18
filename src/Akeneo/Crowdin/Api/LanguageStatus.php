<?php

namespace Akeneo\Crowdin\Api;

/**
 * Project translation progress by language
 *
 * @author Pierre Allard <pierre.allard@akeneo.com>
 * @see http://crowdin.net/page/api/language-status
 */
class LanguageStatus extends AbstractApi
{
    /** @var string */
    protected $language;

    /**
     * @param string $language
     *
     * @return LanguageStatus
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $path = sprintf(
            "project/%s/language-status?key=%s",
            $this->client->getProjectIdentifier(),
            $this->client->getProjectApiKey()
        );
        $parameters = array_merge($this->parameters, ['form_params' => ['language' => $this->getLanguage()]]);
        $response   = $this->client->getHttpClient()->post($path, $parameters);

        return $response->getBody(true);
    }
}
