<?php

namespace Akeneo\Crowdin;

/**
 * Simple Crowdin translation.
 *
 * @author Julien Janvier <j.janvier@gmail.com>
 */
class Translation
{
    /**
     * @var string
     */
    protected $localPath;

    /**
     * @var string
     */
    protected $crowdinPath;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $exportPattern;

    public function __construct($localPath, $crowdinPath)
    {
        $this->setLocalPath($localPath);
        $this->setCrowdinPath($crowdinPath);
    }

    /**
     * @param string $crowdinPath
     */
    public function setCrowdinPath($crowdinPath)
    {
        $this->crowdinPath = $crowdinPath;
    }

    /**
     * @return string
     */
    public function getCrowdinPath()
    {
        return $this->crowdinPath;
    }

    /**
     * @param string $exportPattern
     */
    public function setExportPattern($exportPattern)
    {
        $this->exportPattern = $exportPattern;
    }

    /**
     * @return string
     */
    public function getExportPattern()
    {
        return $this->exportPattern;
    }

    /**
     * @param string $localPath
     *
     * @throws \InvalidArgumentException
     */
    public function setLocalPath($localPath)
    {
        if (!file_exists($localPath)) {
            throw new \InvalidArgumentException(sprintf('File %s does not exist', $localPath));
        }

        $this->localPath = $localPath;
    }

    /**
     * @return string
     */
    public function getLocalPath()
    {
        return $this->localPath;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
}
