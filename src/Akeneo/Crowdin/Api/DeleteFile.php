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

        $this->addUrlParameter('key', $this->client->getProjectApiKey());

        $path = sprintf(
            "project/%s/delete-file?%s",
            $this->client->getProjectIdentifier(),
            $this->getUrlQueryString()
        );

        $parameters = ['file' => $this->file];

        $data = ['body' => $parameters];
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
