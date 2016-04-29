<?php

namespace spec\Akeneo\Crowdin;

use Akeneo\Crowdin\Translation;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FileReaderSpec extends ObjectBehavior
{
    const EXISTING_FILE_PATH = __DIR__ . '/../../fixtures/messages.en.yml';
    const NOT_EXISTING_FILE_PATH = __DIR__ . '/../../fixtures/not_existing.yml';

    public function it_reads_a_stream(Translation $translation)
    {
        $translation->getLocalPath()->willReturn(self::EXISTING_FILE_PATH);
        $this->readTranslation($translation)->shouldBeResource();
    }

    public function it_throws_an_exception_when_file_cannot_be_found(Translation $translation)
    {
        $translation->getLocalPath()->willReturn(self::NOT_EXISTING_FILE_PATH);
        $this
            ->shouldThrow('Akeneo\Crowdin\FileNotFoundException')
            ->during('readTranslation', [$translation]);
    }
}
