<?php

namespace Akeneo\Crowdin\Api;

/**
 * Download ZIP file with translations (all or chosen language)
 *
 * @author Nicolas Dupont <nicolas@akeneo.com>
 * @see https://crowdin.com/page/api/download
 */
class Download extends AbstractApi
{
    /** @var string */
    protected $package = 'all.zip';

    /** @var string */
    protected $copyDestination = '/tmp';

    /** @var string */
    protected $branch;

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $this->addUrlParameter('key', $this->client->getProjectApiKey());
        
        $path = sprintf(
            "project/%s/download/%s?%s",
            $this->client->getProjectIdentifier(),
            $this->package,
            $this->getUrlQueryString()
        );
        if (null !== $this->branch) {
            $path = sprintf('%s&branch=%s', $path, $this->branch);
        }
        $response = $this->client->getHttpClient()->get($path,
            [
                'sink' => $this->getCopyDestination() . '/' . $this->getPackage()
            ]
        );

        return $response->getBody();
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
     *
     * @return Download
     */
    public function setBranch($branch)
    {
        $this->branch = $branch;

        return $this;
    }
}
