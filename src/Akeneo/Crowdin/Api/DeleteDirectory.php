<?php

namespace Akeneo\Crowdin\Api;

/**
 * Delete a directory from the Crowdin project. All nested files and directories will be deleted too.
 *
 * @author Julien Janvier <j.janvier@gmail.com>
 * @see http://crowdin.net/page/api/delete-directory
 */
class DeleteDirectory extends AbstractApi
{
    private $directory;

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        if (null == $this->getDirectory()) {
            throw new \InvalidArgumentException('There is no directory to delete.');
        }

        $path = sprintf(
            "project/%s/delete-directory?key=%s",
            $this->client->getProjectIdentifier(),
            $this->client->getProjectApiKey()
        );

        $parameters = array_merge($this->parameters, array('name' => $this->getDirectory()));

        $request  = $this->client->getHttpClient()->post($path, array(), $parameters);
        $response = $request->send();

        return $response->getBody(true);
    }

    /**
     * @param mixed $directory
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;
    }

    /**
     * @return mixed
     */
    public function getDirectory()
    {
        return $this->directory;
    }
}
