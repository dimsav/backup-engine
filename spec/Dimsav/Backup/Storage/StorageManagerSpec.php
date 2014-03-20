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
        $config = $this->getSimpleDropboxConfig();
        $factory->make($config['name'])->shouldBeCalled()->willReturn($dropboxStorage);

        $this->beConstructedWith($config, $factory);
        $this->storage('name')->shouldHaveType('Dimsav\Backup\Storage\Drivers\DropboxStorage');
    }

    function it_throws_exception_if_project_name_is_not_found($factory)
    {
        $this->beConstructedWith(array(), $factory);
        $this->shouldThrow(new \InvalidArgumentException("Invalid storage 'name'"))->duringStorage('name');
    }

    /**
     * @return array
     */
    private function getSimpleDropboxConfig()
    {
        return array(
            'name' => array(
                'driver' => 'dropbox',
            )
        );
    }

}
