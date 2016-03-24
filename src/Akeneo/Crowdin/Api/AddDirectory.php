<?php

namespace Akeneo\Crowdin\Api;

/**
 * Add a directory to the Crowdin project.
 *
 * @author Julien Janvier <j.janvier@gmail.com>
 * @see http://crowdin.net/page/api/add-directory
 */
class AddDirectory extends AbstractApi
{
    private $directory;

    private $isBranch = false;

    private $branch;

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        if (null == $this->getDirectory()) {
            throw new \InvalidArgumentException('There is no directory to create.');
        }

        $path = sprintf(
            "project/%s/add-directory?key=%s",
            $this->client->getProjectIdentifier(),
            $this->client->getProjectApiKey()
        );

        $parameters = array_merge($this->parameters, array('name' => $this->getDirectory()));
        if ($this->getIsBranch()) {
            $parameters['is_branch'] = '1';
        }
        if (null !== $this->getBranch()) {
            $parameters['branch'] = $this->getBranch();
        }

        $request  = $this->client->getHttpClient()->post($path, array(), $parameters);

        $response = $request->send();

        return $response->getBody(true);
    }

    /**
     * @param mixed $directory
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;
    }

    /**
     * @return mixed
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * @param bool $isBranch
     */
    public function setIsBranch($isBranch)
    {
        $this->isBranch = $isBranch;
    }

    /**
     * @return bool
     */
    public function getIsBranch()
    {
        return $this->isBranch;
    }

    /**
     * @param string $branch
     */
    public function setBranch($branch)
    {
        $this->branch = $branch;
    }

    /**
     * @return string|null
     */
    public function getBranch()
    {
        return $this->branch;
    }
}
