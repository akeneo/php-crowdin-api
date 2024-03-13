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

        $path = sprintf(
            "project/%s/delete-directory",
            $this->client->getProjectIdentifier()
        );

        $parameters = ['name' => $this->directory];

        $data = [
            'headers' => ['authorization' => 'Bearer ' . $this->client->getProjectApiKey()],
            'body' => $parameters
        ];
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
