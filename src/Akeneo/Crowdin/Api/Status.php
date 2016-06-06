<?php

namespace Akeneo\Crowdin\Api;

/**
 * Project translation progress by language
 *
 * @author Nicolas Dupont <nicolas@akeneo.com>
 * @see http://crowdin.net/page/api/status
 */
class Status extends AbstractApi
{
    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $this->addUrlParameter('key', $this->client->getProjectApiKey());
        
        $path = sprintf(
            "project/%s/status?%s",
            $this->client->getProjectIdentifier(),
            $this->getUrlQueryString()
        );
        $response = $this->client->getHttpClient()->get($path);

        return $response->getBody();
    }
}
