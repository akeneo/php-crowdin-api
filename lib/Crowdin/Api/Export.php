<?php

namespace Crowdin\Api;

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
     * @return mixed
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
        $request  = $this->client->getHttpClient()->get($path);
        $response = $request->send();

        return $response->getBody(true);
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
