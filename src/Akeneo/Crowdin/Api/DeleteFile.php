<?php

namespace Akeneo\Crowdin\Api;

use \InvalidArgumentException;

/**
 * Deletes a file from a Crowdin project. All the translations will be lost without ability to restore them.
 *
 * @author Julien Janvier <j.janvier@gmail.com>
 * @see https://crowdin.com/page/api/delete-file
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

        $this->addUrlParameter('key', $this->client->getProjectApiKey());
        
        $path = sprintf(
            "project/%s/delete-file?%s",
            $this->client->getProjectIdentifier(),
            $this->getUrlQueryString()
        );

        $parameters = ['file' => $this->file];

        $data = ['form_params' => $parameters];
        $response = $this->client->getHttpClient()->post($path, $data);

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
