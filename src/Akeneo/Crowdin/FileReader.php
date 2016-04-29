<?php

namespace Akeneo\Crowdin;

/**
 * Reads a stream from the translation local file path
 *
 * @author Nicolas Dupont <nicolas@akeneo.com>
 */
class FileReader
{
    /**
     * @param Translation $translation
     *
     * @return resource
     */
    public function readTranslation(Translation $translation)
    {
        return $this->readStream($translation->getLocalPath());
    }

    /**
     * @return resource
     */
    public function readStream($filename)
    {
        if (!file_exists($filename)) {
            throw new FileNotFoundException(sprintf('The file "%s" does not exists.', $filename));
        }

        $stream = fopen($filename, 'r');
        if (!$stream) {
            throw new FileNotFoundException(sprintf('The file "%s" can\'t be opened.', $filename));
        }

        return $stream;
    }
}
