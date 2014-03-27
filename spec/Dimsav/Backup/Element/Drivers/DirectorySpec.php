<?php

namespace spec\Dimsav\Backup\Element\Drivers;

use Dimsav\Backup\Element\Drivers\Directory;
use Dimsav\UnixZipper;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @mixin Directory
 */
class DirectorySpec extends ObjectBehavior
{
    function it_is_initializable(UnixZipper $zipper)
    {
        $this->beConstructedWith(realpath(__DIR__.'../'), 'Drivers', $zipper);
        $this->shouldHaveType('Dimsav\Backup\Element\Element');
        $this->shouldHaveType('Dimsav\Backup\Element\Drivers\Directory');
        $this->shouldHaveType('Dimsav\Backup\Element\AbstractElement');
    }

    // Validates

    function it_throws_exception_if_root_dir_does_not_exist(UnixZipper $zipper)
    {
        $rootDir = __DIR__.'/Bad';
        $message = "The path '$rootDir' is not valid";
        $this->shouldThrow(new \InvalidArgumentException($message))
            ->during('__construct', array($rootDir, '', $zipper));
    }

    function it_throws_exception_if_the_root_dir_is_not_an_absolute_path(UnixZipper $zipper)
    {
        $rootDir = '../';
        $message = "The path '$rootDir' is not absolute";
        $this->shouldThrow(new \InvalidArgumentException($message))
            ->during('__construct', array($rootDir, '', $zipper));
    }

    function it_throws_exception_if_the_directory_does_not_exist(UnixZipper $zipper)
    {
        $dir = __DIR__.'/bad';
        $message = "The path '$dir' does not exist.";
        $this->shouldThrow(new \InvalidArgumentException($message))
            ->during('__construct', array(__DIR__, 'bad', $zipper));
    }

    function it_throws_exception_if_the_directory_does_not_exist_from_array(UnixZipper $zipper)
    {
        $dir = __DIR__.'/bad';
        $message = "The path '$dir' does not exist.";
        $this->shouldThrow(new \InvalidArgumentException($message))
            ->during('__construct', array(__DIR__, array('bad'), $zipper));
    }

    function it_throws_exception_if_the_directory_is_not_given(UnixZipper $zipper)
    {
        $message = "The directory is not set.";
        $this->shouldThrow(new \InvalidArgumentException($message))
            ->during('__construct', array(__DIR__, array(), $zipper));
    }

    // It parses the directory

    function it_parses_the_dir_if_string_given(UnixZipper $zipper)
    {
        $this->validateDir($this->getParentDir(), 'Drivers', __DIR__, $zipper);
    }

    function it_trims_the_parent_dir_for_ending_slashes(UnixZipper $zipper)
    {
        $this->validateDir($this->getParentDir().'/', '/Drivers/', __DIR__, $zipper);
    }

    function it_trims_the_dir_for_ending_slashes(UnixZipper $zipper)
    {
        $this->validateDir($this->getParentDir(), 'Drivers/', __DIR__, $zipper);
    }

    function it_trims_the_dir_for_starting_slashes(UnixZipper $zipper)
    {
        $this->validateDir($this->getParentDir(), '/Drivers/', __DIR__, $zipper);
    }

    function it_parses_the_project_dir_if_slash_is_given(UnixZipper $zipper)
    {
        $this->validateDir(__DIR__, '/', __DIR__, $zipper);
        $this->beConstructedWith(__DIR__, '/');
        $this->getDir()->shouldReturn(__DIR__);
    }

    // It parses the excludes

    function it_returns_the_excludes_as_array(UnixZipper $zipper)
    {
        $this->beConstructedWith($this->getParentDir(), 'Drivers', $zipper);
        $this->getExcludes()->shouldReturn(array());
    }

    function it_parses_the_excludes(UnixZipper $zipper)
    {
        $dir = array('Drivers', 'excludes' => 'logs');
        $this->beConstructedWith($this->getParentDir(), $dir, $zipper);
        $this->getExcludes()->shouldReturn(array(__DIR__.'/logs'));
    }

    function it_parses_multiple_excludes(UnixZipper $zipper)
    {
        $dir = array('Drivers', 'excludes' => array('logs', 'temp'));
        $this->beConstructedWith($this->getParentDir(), $dir, $zipper);
        $this->getExcludes()->shouldReturn(array(__DIR__.'/logs', __DIR__.'/temp'));
    }

    // It extracts the directory

    function it_extracts_project_files(UnixZipper $zipper)
    {
        $this->construct($zipper);
        $this->setExtractionDir(__DIR__);

        $zipper->add(__DIR__)->shouldBeCalled();
        $zipper->setDestination(__DIR__.'/directories_drivers.zip')->shouldBeCalled();
        $zipper->compress()->shouldBeCalled();

        $this->extract();
        $this->getExtractedFiles()->shouldReturn(array(__DIR__.'/directories_drivers.zip'));
    }

    function it_extracts_with_excludes(UnixZipper $zipper)
    {
        $dir = array('Element', 'excludes'=> array('logs','temp'));
        $this->beConstructedWith(realpath(__DIR__.'/../../'), $dir, $zipper);
        $this->setExtractionDir(__DIR__);

        $zipper->add(realpath(__DIR__.'/../'))->shouldBeCalled();
        $zipper->setDestination(__DIR__.'/directories_element.zip')->shouldBeCalled();
        $zipper->exclude(realpath(__DIR__.'/../').'/logs')->shouldBeCalled();
        $zipper->exclude(realpath(__DIR__.'/../').'/temp')->shouldBeCalled();

        $zipper->compress()->shouldBeCalled();

        $this->extract();
        $this->getExtractedFiles()->shouldReturn(array(__DIR__.'/directories_element.zip'));
    }

    // Helpers

    private function construct($zipper)
    {
        $this->beConstructedWith($this->getParentDir(), $this->getCurrentDir(), $zipper);
    }

    private function getParentDir()
    {
        return realpath(__DIR__.'/..');
    }

    private function getCurrentDir()
    {
        return 'Drivers';
    }

    private function extractionDir()
    {
        return realpath(__DIR__.'/../../../');
    }

    private function validateDir($rootDir, $dir, $result, $zipper)
    {
        $this->beConstructedWith($rootDir, $dir, $zipper);
        $this->getDir()->shouldReturn($result);
    }

}
