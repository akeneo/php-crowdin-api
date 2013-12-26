<?php

namespace Crowdin\Api;

use Crowdin\Client;
use Guzzle\Http\Client as GuzzleClient;

/**
 * API for getting project details
 *
 * @author Nicolas Dupont <nicolas@akeneo.com>
 */
class Info extends AbstractApi
{
    /**
     * @return mixed
     */
    public function execute()
    {
        $options = array();
        $client = new GuzzleClient(Client::BASE_URL, $options);
        $path = sprintf("project/%s/info?key=%s", $this->client->getProjectIdentifier(), $this->client->getProjectApiKey());
        $response = $client->get($path)->send();

        return $response->getBody(true);
    }
}
