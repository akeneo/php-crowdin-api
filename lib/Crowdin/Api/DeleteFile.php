<?php

namespace Crowdin\Api;

/**
 * Deletes a file from a Crowdin project. All the translations will be lost without ability to restore them.
 *
 * @author Julien Janvier <j.janvier@gmail.com>
 * @see http://crowdin.net/page/api/delete-file
 */
class DeleteFile extends AbstractApi
{
    protected $file;

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        if (null == $this->getFile()) {
            throw new \InvalidArgumentException('There is no file to delete.');
        }

        $path = sprintf(
            "project/%s/delete-file?key=%s",
            $this->client->getProjectIdentifier(),
            $this->client->getProjectApiKey()
        );

        $parameters = array_merge($this->parameters, array('file' => $this->getFile()));

        $request  = $this->client->getHttpClient()->post($path, array(), $parameters);
        $response = $request->send();

        return $response->getBody(true);
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }
}
