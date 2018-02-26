<?php

namespace Akeneo\Crowdin\Api;

/**
 * This method exports single translated files from Crowdin.
 *
 * @author Markus Weiland <markus.weiland@mapudo.com>
 * @see https://support.crowdin.com/api/export-file/
 */
class ExportFile extends AbstractApi
{
    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $this->addUrlParameter('key', $this->client->getProjectApiKey());

        $path = sprintf(
            "project/%s/export-file?%s",
            $this->client->getProjectIdentifier(),
            $this->getUrlQueryString()
        );
        $response = $this->client->getHttpClient()->get($path);

        return $response->getBody();
    }
}
