<?php

namespace Akeneo\Crowdin\Api;

use \InvalidArgumentException;
use Akeneo\Crowdin\Client;
use Akeneo\Crowdin\FileReader;
use Akeneo\Crowdin\Translation;

/**
 * Adds a new file to a Crowdin project.
 *
 * @author Julien Janvier <j.janvier@gmail.com>
 * @see https://crowdin.com/page/api/add-file
 */
class AddFile extends AbstractApi
{
    /** @var FileReader */
    protected $fileReader;

    /** @var Translation[] */
    protected $translations;

    /** @var string */
    protected $type;

    /** @var string */
    protected $branch;

    /**
     * @param Client     $client
     * @param FileReader $fileReader
     */
    public function __construct(Client $client, FileReader $fileReader)
    {
        parent::__construct($client);
        $this->fileReader = $fileReader;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        if (0 === count($this->translations)) {
            throw new InvalidArgumentException('There is no files to add.');
        }
        
        $this->addUrlParameter('key', $this->client->getProjectApiKey());

        $path = sprintf(
            "project/%s/add-file?%s",
            $this->client->getProjectIdentifier(),
            $this->getUrlQueryString()
        );

        $data = $this->parameters;
        if (null !== $this->type) {
            $data[] = [
                'name'      => 'type',
                'contents'  => $this->type
            ];
        }
        if (null !== $this->branch) {
            $data[] = [
                'name'      => 'branch',
                'contents'  => $this->branch
            ];
        }
        foreach ($this->translations as $translation) {
            $data[] = [
                'name'      => 'files['.$translation->getCrowdinPath().']',
                'contents'  => $this->fileReader->readTranslation($translation)
            ];
            if ($translation->getTitle()) {
                $data[] = [
                    'name'      => 'titles['.$translation->getCrowdinPath().']',
                    'contents'  => $translation->getTitle()
                ];
            }
            if ($translation->getExportPattern()) {
                $data[] = [
                    'name'      => 'export_patterns['.$translation->getCrowdinPath().']',
                    'contents'  => $translation->getExportPattern()
                ];
            }
        }

        $data = ['multipart' => $data];
        $response = $this->client->getHttpClient()->post($path, $data);

        return $response->getBody();
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
