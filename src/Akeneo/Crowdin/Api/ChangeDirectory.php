<?php

namespace Akeneo\Crowdin\Api;

use InvalidArgumentException;

/**
 * Rename directory or modify its attributes. When renaming directory the path can not be changed (it means new_name
 * parameter can not contain path, name only).
 *
 * @author Pierre Allard <pierre.allard@akeneo.com>
 * @see    https://crowdin.com/page/api/change-directory
 */
class ChangeDirectory extends AbstractApi
{
    protected string $name;
    protected ?string $newName = null;
    protected ?string $title = null;
    protected ?string $exportPattern = null;
    protected ?string $branch = null;

    /**
     * {@inheritdoc}
     */
    public function execute(): string
    {
        if (null == $this->name) {
            throw new InvalidArgumentException('Argument name is required.');
        }

        $this->addUrlParameter('key', $this->client->getProjectApiKey());

        $path = sprintf(
            "project/%s/change-directory?%s",
            $this->client->getProjectIdentifier(),
            $this->getUrlQueryString()
        );

        $data = ['name' => $this->name];

        if (null !== $this->newName) {
            $data['new_name'] = $this->newName;
        }
        if (null !== $this->title) {
            $data['title'] = $this->title;
        }
        if (null !== $this->exportPattern) {
            $data['export_pattern'] = $this->exportPattern;
        }
        if (null !== $this->branch) {
            $data['branch'] = $this->branch;
        }

        $data = ['form_params' => $data];
        $response = $this->client->getHttpClient()->request('POST', $path, $data);

        return $response->getContent();
    }

    public function setBranch(string $branch): static
    {
        $this->branch = $branch;

        return $this;
    }

    /**
     * Set new directory export pattern. Is used to create directory name and path in resulted translations bundle.
     */
    public function setExportPattern(string $exportPattern): static
    {
        $this->exportPattern = $exportPattern;

        return $this;
    }

    /**
     * Set new directory title to be displayed in Crowdin UI.
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Set new directory name.
     */
    public function setNewName(string $newName): static
    {
        $this->newName = $newName;

        return $this;
    }

    /**
     * Set full directory path that should be modified (e.g. /MainPage/AboutUs).
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
