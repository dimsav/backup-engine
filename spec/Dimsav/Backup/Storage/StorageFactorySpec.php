<?php

namespace spec\Dimsav\Backup\Storage;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Illuminate\Container\Container;

class StorageFactorySpec extends ObjectBehavior
{
    function let(Container $container)
    {
        $this->beConstructedWith($container);
    }

    function it_creates_accepts_dropbox_as_driver()
    {
        $config = array('driver' => 'dropbox');
        $this->make($config)->shouldReturnAnInstanceOf('Dimsav\Backup\Storage\Drivers\DropboxStorage');
    }

    function it_creates_accepts_local_as_driver()
    {
        $config = array('driver' => 'local');
        $this->make($config)->shouldReturnAnInstanceOf('Dimsav\Backup\Storage\Drivers\LocalFileStorage');
    }

    function it_throws_exception_if_driver_is_unknown_or_empty()
    {
        $config = array('driver' => 'abc');
        $this->shouldThrow(new \InvalidArgumentException("Invalid storage driver"))->duringMake($config);
    }


}
