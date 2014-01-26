<?php

namespace Crowdin\Api;

/**
 * Add a directory to the Crowdin project.
 *
 * @author Julien Janvier <j.janvier@gmail.com>
 * @see http://crowdin.net/page/api/add-directory
 */
class AddDirectory extends AbstractApi
{
    private $directory;

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        if (null == $this->getDirectory()) {
            throw new \InvalidArgumentException('There is no directory to create.');
        }

        $path = sprintf(
            "project/%s/add-directory?key=%s",
            $this->client->getProjectIdentifier(),
            $this->client->getProjectApiKey()
        );

        $parameters = array('name' => $this->getDirectory());

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
