<?php

namespace Akeneo\Crowdin\Api;

use \InvalidArgumentException;
use Akeneo\Crowdin\Translation;

/**
 * Upload latest version of your localization file to Crowdin.
 *
 * @author Nicolas Dupont <nicolas@akeneo.com>
 * @see http://crowdin.net/page/api/update-file
 */
class UpdateFile extends AbstractApi
{
    /** @var Translation[] */
    protected $translations;

    /** @var string|null */
    protected $branch;

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        if (count($this->translations) === 0) {
            throw new InvalidArgumentException('There is no files to update');
        }
        $path = sprintf(
            "project/%s/update-file?key=%s",
            $this->client->getProjectIdentifier(),
            $this->client->getProjectApiKey()
        );

        $data = $this->parameters;
        if (null !== $this->branch) {
            $data[] = [
                'name'      => 'branch',
                'contents'  => $this->branch
            ];
        }

        foreach ($this->translations as $translation) {
            $data[] = [
                'name'      => 'files['.$translation->getCrowdinPath().']',
                'contents'  => '@'.$translation->getLocalPath()
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
     * @return null|string
     */
    public function getBranch()
    {
        return $this->branch;
    }

    /**
     * @param $branch
     */
    public function setBranch($branch)
    {
        $this->branch = $branch;
    }
}
