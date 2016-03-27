<?php

namespace spec\Akeneo\Crowdin;

use \InvalidArgumentException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TranslationSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(__DIR__ . '/../../fixtures/messages.en.yml', 'crowdin_path');
    }

    public function it_has_a_local_path()
    {
        $this->getLocalPath()->shouldReturn(__DIR__ . '/../../fixtures/messages.en.yml');
    }

    public function it_has_a_crowdin_path()
    {
        $this->getCrowdinPath()->shouldReturn('crowdin_path');
    }

    public function it_has_no_title_by_default()
    {
        $this->getTitle()->shouldReturn(null);
    }

    public function it_has_no_export_pattern_by_default()
    {
        $this->getExportPattern()->shouldReturn(null);
    }

    public function its_local_path_should_be_mutable()
    {
        $this->setLocalPath(__DIR__ . '/../../fixtures/messages.en.yml');
        $this->getLocalPath()->shouldReturn(__DIR__ . '/../../fixtures/messages.en.yml');
    }

    public function its_crowdin_path_should_be_mutable()
    {
        $this->setCrowdinPath('my/path/to/crowdin.yml');
        $this->getCrowdinPath()->shouldReturn('my/path/to/crowdin.yml');
    }

    public function its_title_path_should_be_mutable()
    {
        $this->setTitle('The title of my translation');
        $this->getTitle()->shouldReturn('The title of my translation');
    }

    public function its_export_pattern_should_be_mutable()
    {
        $this->setExportPattern('my/path/to/crowdin%two_letters_code%.yml');
        $this->getExportPattern()->shouldReturn('my/path/to/crowdin%two_letters_code%.yml');
    }

    public function it_should_trow_an_exception_when_a_local_file_does_not_exist()
    {
        $this
            ->shouldThrow(new InvalidArgumentException('File local_path does not exist'))
            ->duringSetLocalPath('local_path')
        ;
    }
}
