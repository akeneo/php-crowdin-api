<?php

namespace Crowdin\Api;

/**
 * Upload latest version of your localization file to Crowdin.
 *
 * @author Nicolas Dupont <nicolas@akeneo.com>
 * @see http://crowdin.net/page/api/update-file
 */
class UpdateFile extends AbstractApi
{
    /**
     * @var array
     */
    protected $files;

    /**
     * @return mixed
     */
    public function execute()
    {
        if (count($this->files) === 0) {
            throw new \InvalidArgumentException('There is no files to update');
        }
        $path = sprintf(
            "project/%s/update-file?key=%s",
            $this->client->getProjectIdentifier(),
            $this->client->getProjectApiKey()
        );
        $data = array();
        foreach ($this->files as $crowdinPath => $localFile) {
            $data['files['.$crowdinPath.']']= '@'.$localFile;
        }

        $request = $this->client->getHttpClient()->post($path, array(), $data);
        $response = $request->send();

        return $response->getBody(true);
    }

    /**
     * @param string $crowdinPath the Crowdin file path
     * @param string $localPath   the local file path
     *
     * @return UpdateFile
     */
    public function addFile($crowdinPath, $localPath)
    {
        if (!file_exists($localPath)) {
            throw new \InvalidArgumentException(sprintf('File %s not exists', $localPath));
        }
        $this->files[$crowdinPath]= $localPath;

        return $this;
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }
}
