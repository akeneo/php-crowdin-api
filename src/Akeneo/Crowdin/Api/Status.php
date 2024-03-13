<?php

namespace Akeneo\Crowdin\Api;

/**
 * Project translation progress by language
 *
 * @author Nicolas Dupont <nicolas@akeneo.com>
 * @see    https://crowdin.com/page/api/status
 */
class Status extends AbstractApi
{
    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $path = sprintf(
            "project/%s/status",
            $this->client->getProjectIdentifier()
        );
        $response = $this->client->getHttpClient()->request(
            'GET',
            $path,
            ['headers' => ['authorization' => 'Bearer ' . $this->client->getProjectApiKey()]]
        );

        return $response->getContent();
    }
}
