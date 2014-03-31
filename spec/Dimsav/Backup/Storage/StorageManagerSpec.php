<?php namespace spec\Dimsav\Backup\Storage;

use Dimsav\Backup\Storage\Drivers\Dropbox;
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
        $this->beConstructedWith(array('storages' => array()), $factory);
        $this->shouldHaveType('Dimsav\Backup\Storage\StorageManager');
    }

    function it_returns_storages($factory, Dropbox $dropbox)
    {
        $config = $this->getSimpleDropboxConfig();
        $factory->make($config['storages']['name'])->shouldBeCalled()->willReturn($dropbox);

        $this->beConstructedWith($config, $factory);
        $this->make('name')->shouldHaveType('Dimsav\Backup\Storage\Drivers\Dropbox');
    }

    function it_throws_exception_if_storages_is_not_set($factory)
    {
        $this->shouldThrow(new \InvalidArgumentException("Storages array is not in configuration"))
            ->during('__construct', array(array(), $factory));
    }

    function it_throws_exception_if_storages_is_not_an_array($factory)
    {
        $this->shouldThrow(new \InvalidArgumentException("Storages array is not in configuration"))
            ->during('__construct', array(array('storages' => 1), $factory));
    }

    function it_throws_exception_if_no_storage_is_defined($factory)
    {
        $this->shouldThrow(new \InvalidArgumentException("Storages array is empty. Check configuration."))
            ->during('__construct', array(array('storages' => array()), $factory));
    }

    function it_throws_exception_if_project_name_is_not_found($factory)
    {
        $this->beConstructedWith(array('storages' => array('a'=>array())), $factory);
        $this->shouldThrow(new \InvalidArgumentException("Invalid storage 'name'"))->duringMake('name');
    }

    /**
     * @return array
     */
    private function getSimpleDropboxConfig()
    {
        return array(
            'storages' => array(
                'name' => array(
                    'driver' => 'dropbox',
                )
        ));
    }

}
