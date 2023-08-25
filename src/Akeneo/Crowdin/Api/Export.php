<?php

namespace Akeneo\Crowdin\Api;

/**
 * Build ZIP archive with the latest translations. Can be invoked only once for 30 minutes.
 *
 * @author Nicolas Dupont <nicolas@akeneo.com>
 * @see    https://crowdin.com/page/api/export
 */
class Export extends AbstractApi
{
    protected ?string $branch = null;

    /**
     * {@inheritdoc}
     */
    public function execute(): string
    {
        $this->addUrlParameter('key', $this->client->getProjectApiKey());

        $path = sprintf(
            "project/%s/export?%s",
            $this->client->getProjectIdentifier(),
            $this->getUrlQueryString()
        );
        if (null !== $this->branch) {
            $path = sprintf('%s&branch=%s', $path, $this->branch);
        }
        $response = $this->client->getHttpClient()->request('GET', $path);

        return $response->getContent();
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
