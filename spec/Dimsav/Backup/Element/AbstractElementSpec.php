<?php

namespace spec\Dimsav\Backup\Element;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AbstractElementSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf('spec\Dimsav\Backup\Element\AbstractElementExtension');
    }

    function it_implements_interface()
    {
        $this->shouldHaveType('\Dimsav\Backup\Element\Element');
    }

    function it_receives_an_extraction_directory()
    {
        $this->setExtractionDir(__DIR__.'/../Element');
    }

    function it_throws_an_exception_if_extraction_dir_not_valid()
    {
        $dir = __DIR__.'/bad';
        $exception = new \InvalidArgumentException("The directory '$dir' does not exist.");
        $this->shouldThrow($exception)->duringSetExtractionDir($dir);
    }

    function it_throws_an_exception_if_extraction_dir_not_an_absolute_path()
    {
        $dir = '../';
        $exception = new \InvalidArgumentException("The directory '$dir' is not an absolute path.");
        $this->shouldThrow($exception)->duringSetExtractionDir($dir);
    }

}

class AbstractElementExtension extends \Dimsav\Backup\Element\AbstractElement {

    /**
     * Saves the files into the extraction directory
     */
    public function extract() {}

    /**
     * Returns the absolute path of the files created upon extract()
     */
    public function getExtractedFiles() {}
}