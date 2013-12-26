<?php

namespace Crowdin\Api;

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
        $http     = $this->client->getHttpClient();
        $request  = $http->get('supported-languages');
        $response = $request->send();

        return $response->getBody(true);
    }
}
