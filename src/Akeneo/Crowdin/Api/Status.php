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
