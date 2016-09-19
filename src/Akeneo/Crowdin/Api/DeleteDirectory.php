<?php

namespace Akeneo\Crowdin\Api;

use \InvalidArgumentException;

/**
 * Delete a directory from the Crowdin project. All nested files and directories will be deleted too.
 *
 * @author Julien Janvier <j.janvier@gmail.com>
 * @see https://crowdin.com/page/api/delete-directory
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

        $this->addUrlParameter('key', $this->client->getProjectApiKey());
        
        $path = sprintf(
            "project/%s/delete-directory?%s",
            $this->client->getProjectIdentifier(),
            $this->getUrlQueryString()
        );

        $parameters = ['name' => $this->directory];

        $data = ['form_params' => $parameters];
        $response = $this->client->getHttpClient()->post($path, $data);

        return $response->getBody();
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
