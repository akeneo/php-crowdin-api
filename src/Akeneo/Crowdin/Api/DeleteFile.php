<?php

namespace Akeneo\Crowdin\Api;

use InvalidArgumentException;

/**
 * Deletes a file from a Crowdin project. All the translations will be lost without ability to restore them.
 *
 * @author Julien Janvier <j.janvier@gmail.com>
 * @see https://crowdin.com/page/api/delete-file
 */
class DeleteFile extends AbstractApi
{
    protected string $file;

    /**
     * {@inheritdoc}
     */
    public function execute(): string
    {
        if (null == $this->file) {
            throw new InvalidArgumentException('There is no file to delete.');
        }

        $path = sprintf(
            "project/%s/delete-file",
            $this->client->getProjectIdentifier()
        );

        $parameters = ['file' => $this->file];

        $data = [
            'headers' => ['authorization' => 'Bearer ' . $this->client->getProjectApiKey()],
            'body' => $parameters
        ];
        $response = $this->client->getHttpClient()->request('POST', $path, $data);

        return $response->getContent();
    }

    public function setFile(string $file): static
    {
        $this->file = $file;

        return $this;
    }

    public function getFile(): string
    {
        return $this->file;
    }
}
