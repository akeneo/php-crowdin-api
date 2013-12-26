<?php

namespace Crowdin\Api;

use Crowdin\Client;
use Guzzle\Http\Client as GuzzleClient;

/**
 * API for getting supported languages
 *
 * @author Nicolas Dupont <nicolas@akeneo.com>
 */
class SupportedLanguages extends AbstractApi
{
    /**
     * @return mixed
     */
    public function execute()
    {
        $options = array();
        $client = new GuzzleClient(Client::BASE_URL, $options);
        $response = $client->get('supported-languages')->send();

        return $response->getBody(true);
    }
}
