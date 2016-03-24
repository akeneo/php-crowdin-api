<?php

namespace Akeneo\Crowdin\Api;

/**
 * Get project details
 *
 * @author Nicolas Dupont <nicolas@akeneo.com>
 * @see http://crowdin.net/page/api/info
 */
class Info extends AbstractApi
{
    /**
     * @return mixed
     */
    public function execute()
    {
        $path = sprintf(
            "project/%s/info?key=%s",
            $this->client->getProjectIdentifier(),
            $this->client->getProjectApiKey()
        );
        $request = $this->client->getHttpClient()->get($path);
        $response = $request->send();

        return $response->getBody(true);
    }
}
