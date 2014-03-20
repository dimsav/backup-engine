<?php namespace spec\Dimsav\Backup\Storage;

use Dimsav\Backup\Storage\Drivers\DropboxStorage;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class StorageManagerSpec extends ObjectBehavior
{
    function let($factory)
    {
        $factory->beADoubleOf('Dimsav\Backup\Storage\StorageFactory');
    }
    function it_is_initializable($factory)
    {
        $this->beConstructedWith(array(), $factory);
        $this->shouldHaveType('Dimsav\Backup\Storage\StorageManager');
    }

    function it_returns_storages($factory, DropboxStorage $dropboxStorage)
    {
        $config = array(
                'name' => array(
                    'driver' => 'dropbox',
                )
        );

        $this->beConstructedWith($config, $factory);

        $factory->make($config['name'])->shouldBeCalled()->willReturn($dropboxStorage);
        $this->storage('name')->shouldHaveType('Dimsav\Backup\Storage\Drivers\DropboxStorage');
    }

}
