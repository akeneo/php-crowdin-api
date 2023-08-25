<?php

namespace Akeneo\Crowdin\Api;

use InvalidArgumentException;

/**
 * Delete a directory from the Crowdin project. All nested files and directories will be deleted too.
 *
 * @author Julien Janvier <j.janvier@gmail.com>
 * @see    https://crowdin.com/page/api/delete-directory
 */
class DeleteDirectory extends AbstractApi
{
    protected string $directory;

    /**
     * {@inheritdoc}
     */
    public function execute(): string
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
        $response = $this->client->getHttpClient()->request('POST', $path, $data);

        return $response->getContent();
    }

    public function setDirectory(string $directory): static
    {
        $this->directory = $directory;

        return $this;
    }

    public function getDirectory(): string
    {
        return $this->directory;
    }
}
