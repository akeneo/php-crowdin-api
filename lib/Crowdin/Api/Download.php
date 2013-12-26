<?php

namespace Crowdin\Api;

/**
 * API to download the translations (not the last one but the last builded)
 *
 * @author Nicolas Dupont <nicolas@akeneo.com>
 */
class Download extends AbstractApi
{
    /**
     * @var string $package
     */
    protected $package = 'all.zip';

    /**
     * @var string $copyDestination
     */
    protected $copyDestination = '/tmp';

    /**
     * @return mixed
     */
    public function execute()
    {
        $path = sprintf(
            "project/%s/download/%s?key=%s",
            $this->client->getProjectIdentifier(),
            $this->getPackage(),
            $this->client->getProjectApiKey()
        );
        $request = $this->client->getHttpClient()->get($path);
        $response = $request
            ->setResponseBody($this->copyDestination.DIRECTORY_SEPARATOR.$this->getPackage())
            ->send();

        return $response->getBody(true);
    }

    /**
     * @param string $package
     *
     * @return Download
     */
    public function setPackage($package)
    {
        $this->package = $package;

        return $this;
    }

    /**
     * @return string
     */
    public function getPackage()
    {
        return $this->package;
    }

    /**
     * @param string $dest
     *
     * @return Download
     */
    public function setCopyDestination($dest)
    {
        $this->copyDestination = $dest;

        return $this;
    }

    /**
     * @return string
     */
    public function getCopyDestination()
    {
        return $this->copyDestination;
    }
}
