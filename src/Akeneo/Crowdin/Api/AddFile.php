<?php

namespace Akeneo\Crowdin\Api;

use Akeneo\Crowdin\Translation;
use \InvalidArgumentException;

/**
 * Adds a new file to a Crowdin project.
 *
 * @author Julien Janvier <j.janvier@gmail.com>
 * @see https://crowdin.net/page/api/add-file
 */
class AddFile extends AbstractApi
{
    /** @var Translation[] */
    protected $translations;

    /** @var string */
    protected $type;

    /** @var string */
    protected $branch;

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        if (0 === count($this->translations)) {
            throw new InvalidArgumentException('There is no files to add.');
        }

        $path = sprintf(
            "project/%s/add-file?key=%s",
            $this->client->getProjectIdentifier(),
            $this->client->getProjectApiKey()
        );

        $data = $this->parameters;
        if (null !== $this->type) {
            $data['type'] = $this->type;
        }
        if (null !== $this->branch) {
            $data['branch'] = $this->branch;
        }

        foreach ($this->translations as $translation) {
            $data['files['.$translation->getCrowdinPath().']'] = '@'.$translation->getLocalPath();
            if ($translation->getTitle()) {
                $data['titles['.$translation->getCrowdinPath().']'] = $translation->getTitle();
            }
            if ($translation->getExportPattern()) {
                $data['export_patterns['.$translation->getCrowdinPath().']'] = $translation->getExportPattern();
            }
        }

        $request = $this->client->getHttpClient()->post($path, [], $data);
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
     *
     * @return AddFile
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
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
     *
     * @return AddFile
     */
    public function setBranch($branch)
    {
        $this->branch = $branch;

        return $this;
    }
}
