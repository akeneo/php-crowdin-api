<?php

namespace Crowdin\Api;

/**
 * API for getting project translation status
 *
 * @author Nicolas Dupont <nicolas@akeneo.com>
 */
class Status extends AbstractApi
{
    /**
     * @return mixed
     */
    public function execute()
    {
        $path = sprintf(
            "project/%s/status?key=%s",
            $this->client->getProjectIdentifier(),
            $this->client->getProjectApiKey()
        );
        $request = $this->client->getHttpClient()->get($path);
        $response = $request->send();

        return $response->getBody(true);
    }
}
