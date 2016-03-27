<?php

namespace Akeneo\Crowdin\Api;

use \InvalidArgumentException;

/**
 * Delete a directory from the Crowdin project. All nested files and directories will be deleted too.
 *
 * @author Julien Janvier <j.janvier@gmail.com>
 * @see http://crowdin.net/page/api/delete-directory
 */
class DeleteDirectory extends AbstractApi
{
    /** @var string */
    protected $directory;

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        if (null == $this->directory) {
            throw new InvalidArgumentException('There is no directory to delete.');
        }

        $path = sprintf(
            "project/%s/delete-directory?key=%s",
            $this->client->getProjectIdentifier(),
            $this->client->getProjectApiKey()
        );

        $parameters = array_merge($this->parameters, ['name' => $this->directory]);

        $request  = $this->client->getHttpClient()->post($path, [], $parameters);
        $response = $request->send();

        return $response->getBody(true);
    }

    /**
     * @param string $directory
     *
     * @return DeleteDirectory
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;

        return $this;
    }

    /**
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }
}
