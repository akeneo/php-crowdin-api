<?php

namespace Crowdin\Api;

/**
 * Download ZIP file with translations (all or chosen language)
 *
 * @author Nicolas Dupont <nicolas@akeneo.com>
 * @see http://crowdin.net/page/api/download
 */
class Download extends AbstractApi
{
    /** @var string $package */
    protected $package = 'all.zip';

    /** @var string $copyDestination */
    protected $copyDestination = '/tmp';

    /** @var string */
    protected $branch;

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
        if (null !== $this->branch) {
            $path = sprintf('%s&branch=%s', $path, $this->branch);
        }
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

    /**
     * @return string
     */
    public function getBranch()
    {
        return $this->branch;
    }

    /**
     * @param string $branch
     */
    public function setBranch($branch)
    {
        $this->branch = $branch;
    }
}
