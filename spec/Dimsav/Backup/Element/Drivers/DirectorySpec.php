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
    function it_is_initializable()
    {
        $this->beConstructedWith(realpath(__DIR__.'../'), 'Drivers');
        $this->shouldHaveType('Dimsav\Backup\Element\Element');
        $this->shouldHaveType('Dimsav\Backup\Element\Drivers\Directory');
        $this->shouldHaveType('Dimsav\Backup\Element\AbstractElement');
    }

    // Validates

    function it_throws_exception_if_root_dir_does_not_exist()
    {
        $rootDir = __DIR__.'/Bad';
        $message = "The path '$rootDir' is not valid";
        $this->shouldThrow(new \InvalidArgumentException($message))
            ->during('__construct', array($rootDir, ''));
    }

    function it_throws_exception_if_the_root_dir_is_not_an_absolute_path()
    {
        $rootDir = '../';
        $message = "The path '$rootDir' is not absolute";
        $this->shouldThrow(new \InvalidArgumentException($message))
            ->during('__construct', array($rootDir, ''));
    }

    function it_throws_exception_if_the_directory_does_not_exist()
    {
        $dir = __DIR__.'/bad';
        $message = "The path '$dir' does not exist.";
        $this->shouldThrow(new \InvalidArgumentException($message))
            ->during('__construct', array(__DIR__,'bad'));
    }

    function it_throws_exception_if_the_directory_does_not_exist_from_array()
    {
        $dir = __DIR__.'/bad';
        $message = "The path '$dir' does not exist.";
        $this->shouldThrow(new \InvalidArgumentException($message))
            ->during('__construct', array(__DIR__, array('bad')));
    }

    function it_throws_exception_if_the_directory_is_not_given()
    {
        $message = "The directory is not set.";
        $this->shouldThrow(new \InvalidArgumentException($message))
            ->during('__construct', array(__DIR__, array()));
    }

    // It parses the directory

    function it_parses_the_dir_if_string_given()
    {
        $this->validateDir($this->getParentDir(), 'Drivers', __DIR__);
    }

    function it_trims_the_parent_dir_for_ending_slashes()
    {
        $this->validateDir($this->getParentDir().'/', '/Drivers/', __DIR__);
    }

    function it_trims_the_dir_for_ending_slashes()
    {
        $this->validateDir($this->getParentDir(), 'Drivers/', __DIR__);
    }

    function it_trims_the_dir_for_starting_slashes()
    {
        $this->validateDir($this->getParentDir(), '/Drivers/', __DIR__);
    }

    function it_parses_the_project_dir_if_slash_is_given()
    {
        $this->validateDir(__DIR__, '/', __DIR__);
        $this->beConstructedWith(__DIR__, '/');
        $this->getDir()->shouldReturn(__DIR__);
    }

    // It parses the excludes

    function it_returns_the_excludes_as_array()
    {
        $this->beConstructedWith($this->getParentDir(), 'Drivers');
        $this->getExcludes()->shouldReturn(array());
    }

    function it_parses_the_excludes()
    {
        $dir = array('Drivers', 'excludes' => 'logs');
        $this->beConstructedWith($this->getParentDir(), $dir);
        $this->getExcludes()->shouldReturn(array(__DIR__.'/logs'));
    }

    function it_parses_multiple_excludes()
    {
        $dir = array('Drivers', 'excludes' => array('logs', 'temp'));
        $this->beConstructedWith($this->getParentDir(), $dir);
        $this->getExcludes()->shouldReturn(array(__DIR__.'/logs', __DIR__.'/temp'));
    }

    // It extracts

    function it_extracts_the_files(UnixZipper $zipper)
    {
        $this->beConstructedWith($this->getParentDir(), $this->getCurrentDir());

    }

    // Helpers

    private function getParentDir()
    {
        return realpath(__DIR__.'/..');
    }

    private function getCurrentDir()
    {
        return 'Drivers';
    }

    /**
     * @param $rootDir
     * @param $dir
     * @param $result
     */
    protected function validateDir($rootDir, $dir, $result)
    {
        $this->beConstructedWith($rootDir, $dir);
        $this->getDir()->shouldReturn($result);
    }

}
