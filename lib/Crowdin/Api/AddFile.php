<?php

namespace Crowdin\Api;

use Crowdin\Translation;

/**
 * Adds a new file to a Crowdin project.
 *
 * @author Julien Janvier <j.janvier@gmail.com>
 * @see https://crowdin.net/page/api/add-file
 */
class AddFile extends AbstractApi
{
    /**
     * @var Translation[]
     */
    protected $translations;

    /**
     * @var string
     */
    protected $type;

    protected $branch;

    /**
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function execute()
    {
        if (0 === count($this->translations)) {
            throw new \InvalidArgumentException('There is no files to add');
        }

        $path = sprintf(
            "project/%s/add-file?key=%s",
            $this->client->getProjectIdentifier(),
            $this->client->getProjectApiKey()
        );

        $data = $this->parameters;
        if (null !== $this->getType()) {
            $data['type'] = $this->type;
        }
        if (null !== $this->getBranch()) {
            $data['branch'] = $this->getBranch();
        }

        foreach ($this->getTranslations() as $translation) {
            $data['files['.$translation->getCrowdinPath().']'] = '@'.$translation->getLocalPath();
            if ($translation->getTitle()) {
                $data['titles['.$translation->getCrowdinPath().']'] = $translation->getTitle();
            }
            if ($translation->getExportPattern()) {
                $data['export_patterns['.$translation->getCrowdinPath().']'] = $translation->getExportPattern();
            }
        }

        $request = $this->client->getHttpClient()->post($path, array(), $data);
        $response = $request->send();

        return $response->getBody(true);
    }

    /**
     * @param string $localPath
     * @param string $crowdinPath
     * @param string $exportPattern
     * @param string $title
     *
     * @return $this
     */
    public function addTranslation($localPath, $crowdinPath, $exportPattern = null, $title = null)
    {
        $translation = new Translation($localPath, $crowdinPath);
        $translation->setExportPattern($exportPattern);
        $translation->setTitle($title);

        $this->translations[] = $translation;

        return $this;
    }

    /**
     * @return Translation[]
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string|null
     */
    public function getBranch()
    {
        return $this->branch;
    }

    /**
     * @param string $branch
     */
    public function setBranch($branch)
    {
        $this->branch = $branch;
    }
}
