<?php

namespace Akeneo\Crowdin\Api;

/**
 * Download ZIP file with translations (all or chosen language)
 *
 * @author Nicolas Dupont <nicolas@akeneo.com>
 * @see    https://crowdin.com/page/api/download
 */
class Download extends AbstractApi
{
    protected string $package = 'all.zip';
    protected string $copyDestination = '/tmp';
    protected ?string $branch = null;

    /**
     * {@inheritdoc}
     */
    public function execute(): string
    {
        $path = sprintf(
            "project/%s/download/%s",
            $this->client->getProjectIdentifier(),
            $this->package
        );
        if (null !== $this->branch) {
            $path = sprintf('%s?branch=%s', $path, $this->branch);
        }

        $filePath = $this->getCopyDestination() . '/' . $this->getPackage();
        $response = $this->client->getHttpClient()->request('GET', $path, ['headers' => ['Authorization' => 'Bearer 1234']]);

        $fileContent = $response->getContent();
        file_put_contents($filePath, $fileContent);

        return $fileContent;
    }

    public function setPackage(string $package): static
    {
        $this->package = $package;

        return $this;
    }

    public function getPackage(): string
    {
        return $this->package;
    }

    public function setCopyDestination(string $dest): static
    {
        $this->copyDestination = $dest;

        return $this;
    }

    public function getCopyDestination(): string
    {
        return $this->copyDestination;
    }

    public function getBranch(): ?string
    {
        return $this->branch;
    }

    public function setBranch(string $branch): static
    {
        $this->branch = $branch;

        return $this;
    }
}
