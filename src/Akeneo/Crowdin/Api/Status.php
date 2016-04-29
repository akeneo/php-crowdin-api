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
        $path = sprintf(
            "project/%s/status?key=%s",
            $this->client->getProjectIdentifier(),
            $this->client->getProjectApiKey()
        );
        $response = $this->client->getHttpClient()->get($path);

        return $response->getBody();
    }
}
