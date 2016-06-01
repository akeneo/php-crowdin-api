<?php

namespace Akeneo\Crowdin\Api;

use \InvalidArgumentException;

/**
 * Rename directory or modify its attributes. When renaming directory the path can not be changed (it means new_name
 * parameter can not contain path, name only).
 *
 * @author Pierre Allard <pierre.allard@akeneo.com>
 * @see https://crowdin.com/page/api/change-directory
 */
class ChangeDirectory extends AbstractApi
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $newName;

    /** @var string */
    protected $title;

    /** @var string */
    protected $exportPattern;

    /** @var string */
    protected $branch;

    /**
     * {@inheritdoc}
     */
    public function execute()
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
        $response = $this->client->getHttpClient()->post($path, $data);

        return $response->getBody();
    }

    /**
     * @param string $branch
     *
     * @return ChangeDirectory
     */
    public function setBranch($branch)
    {
        $this->branch = $branch;

        return $this;
    }

    /**
     * Set new directory export pattern. Is used to create directory name and path in resulted translations bundle.
     *
     * @param string $exportPattern
     *
     * @return ChangeDirectory
     */
    public function setExportPattern($exportPattern)
    {
        $this->exportPattern = $exportPattern;

        return $this;
    }

    /**
     * Set new directory title to be displayed in Crowdin UI.
     *
     * @param string $title
     *
     * @return ChangeDirectory
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Set new directory name.
     *
     * @param string $newName
     *
     * @return ChangeDirectory
     */
    public function setNewName($newName)
    {
        $this->newName = $newName;

        return $this;
    }

    /**
     * Set full directory path that should be modified (e.g. /MainPage/AboutUs).
     *
     * @param string $name
     *
     * @return ChangeDirectory
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
