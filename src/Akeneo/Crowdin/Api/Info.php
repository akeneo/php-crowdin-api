<?php

namespace Akeneo\Crowdin\Api;

/**
 * Get project details
 *
 * @author Nicolas Dupont <nicolas@akeneo.com>
 * @see    https://crowdin.com/page/api/info
 */
class Info extends AbstractApi
{
    /**
     * {@inheritdoc}
     */
    public function execute(): string
    {
        $path = sprintf(
            "project/%s/info",
            $this->client->getProjectIdentifier()
        );
        $response = $this->client->getHttpClient()->request(
            'GET',
            $path,
            ['headers' => ['Authorization' => 'Bearer ' . $this->client->getProjectApiKey()]]
        );

        return $response->getContent();
    }
}
