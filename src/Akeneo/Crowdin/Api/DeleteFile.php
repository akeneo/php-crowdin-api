<?php

namespace Akeneo\Crowdin\Api;

use \InvalidArgumentException;

/**
 * Deletes a file from a Crowdin project. All the translations will be lost without ability to restore them.
 *
 * @author Julien Janvier <j.janvier@gmail.com>
 * @see http://crowdin.net/page/api/delete-file
 */
class DeleteFile extends AbstractApi
{
    /** @var string */
    protected $file;

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        if (null == $this->file) {
            throw new InvalidArgumentException('There is no file to delete.');
        }

        $path = sprintf(
            "project/%s/delete-file?key=%s",
            $this->client->getProjectIdentifier(),
            $this->client->getProjectApiKey()
        );

        $parameters = array_merge($this->parameters, ['file' => $this->file]);

        $response = $this->client->getHttpClient()->post($path, $parameters);

        return $response->getBody();
    }

    /**
     * @param mixed $file
     *
     * @return DeleteFile
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }
}
