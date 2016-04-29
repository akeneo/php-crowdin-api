<?php

namespace Akeneo\Crowdin\Api;

/**
 * Build ZIP archive with the latest translations. Can be invoked only once for 30 minutes.
 *
 * @author Nicolas Dupont <nicolas@akeneo.com>
 * @see http://crowdin.net/page/api/export
 */
class Export extends AbstractApi
{
    /** @var string */
    protected $branch;

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $path = sprintf(
            "project/%s/export?key=%s",
            $this->client->getProjectIdentifier(),
            $this->client->getProjectApiKey()
        );
        if (null !== $this->branch) {
            $path = sprintf('%s&branch=%s', $path, $this->branch);
        }
        $response = $this->client->getHttpClient()->get($path);

        return $response->getBody();
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
     * @return Export
     */
    public function setBranch($branch)
    {
        $this->branch = $branch;

        return $this;
    }
}
