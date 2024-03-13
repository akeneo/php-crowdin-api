<?php

namespace Akeneo\Crowdin\Api;

use InvalidArgumentException;

/**
 * Add a directory to the Crowdin project.
 *
 * @author Julien Janvier <j.janvier@gmail.com>
 * @see    https://crowdin.com/page/api/add-directory
 */
class AddDirectory extends AbstractApi
{
    protected string $directory;
    protected bool $isBranch = false;
    protected ?string $branch = null;

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        if (null == $this->directory) {
            throw new InvalidArgumentException('There is no directory to create.');
        }

        $path = sprintf(
            "project/%s/add-directory",
            $this->client->getProjectIdentifier()
        );

        $parameters = ['name' => $this->directory];
        if ($this->isBranch) {
            $parameters['is_branch'] = '1';
        }
        if (null !== $this->branch) {
            $parameters['branch'] = $this->branch;
        }

        $data = [
            'headers' => [
                'authorization' => 'Bearer ' . $this->client->getProjectApiKey()
            ],
            'body' => $parameters
        ];
        $response = $this->client->getHttpClient()->request('POST', $path, $data);

        return $response->getContent();
    }

    public function setDirectory(mixed $directory): void
    {
        $this->directory = $directory;
    }

    public function getDirectory(): string
    {
        return $this->directory;
    }

    public function setIsBranch(bool $isBranch): void
    {
        $this->isBranch = $isBranch;
    }

    public function getIsBranch(): bool
    {
        return $this->isBranch;
    }

    public function setBranch(string $branch): void
    {
        $this->branch = $branch;
    }

    public function getBranch(): ?string
    {
        return $this->branch;
    }
}
