<?php

namespace Akeneo\Crowdin;

use InvalidArgumentException;

/**
 * Simple Crowdin translation.
 *
 * @author Julien Janvier <j.janvier@gmail.com>
 */
class Translation
{
    protected string $localPath;
    protected string $crowdinPath;
    protected ?string $title = null;
    protected ?string $exportPattern = null;

    public function __construct(string $localPath, string $crowdinPath)
    {
        $this->setLocalPath($localPath);
        $this->setCrowdinPath($crowdinPath);
    }

    public function setCrowdinPath(string $crowdinPath): void
    {
        $this->crowdinPath = $crowdinPath;
    }

    public function getCrowdinPath(): string
    {
        return $this->crowdinPath;
    }

    public function setExportPattern(?string $exportPattern): void
    {
        $this->exportPattern = $exportPattern;
    }

    public function getExportPattern(): ?string
    {
        return $this->exportPattern;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function setLocalPath(string $localPath): void
    {
        if (!file_exists($localPath)) {
            throw new InvalidArgumentException(sprintf('File %s does not exist', $localPath));
        }

        $this->localPath = $localPath;
    }

    public function getLocalPath(): string
    {
        return $this->localPath;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }
}
