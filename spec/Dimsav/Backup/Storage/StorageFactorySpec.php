<?php namespace spec\Dimsav\Backup\Storage;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class StorageFactorySpec extends ObjectBehavior
{

    private $tokenFile;

    function let()
    {
        $tokenDir = __DIR__.'/../../../../config/tokens';
        $this->tokenFile = $tokenDir.'/.dropbox_storage_3';
        touch($this->tokenFile);
    }

    // It validates input

    function it_throws_exception_if_storage_name_not_set()
    {
        $this->beConstructedWith(array());
        $this->shouldThrow('Dimsav\Backup\Storage\Exceptions\StorageNotFoundException')
            ->duringMake('name');
    }

    function it_throws_exception_if_driver_is_not_set()
    {
        $this->beConstructedWith($this->getConfig());
        $this->shouldThrow('Dimsav\Backup\Storage\Exceptions\StorageDriverNotDefinedException')
            ->duringMake('storage_1');
    }

    function it_throws_exception_if_driver_is_not_supported()
    {
        $this->beConstructedWith($this->getConfig());
        $this->shouldThrow('Dimsav\Backup\Storage\Exceptions\StorageDriverNotSupportedException')
            ->duringMake('storage_2');
    }


    // It creates driver instances

    function it_makes_a_dropbox_instance()
    {
        $this->beConstructedWith($this->getConfig());
        $this->make('storage_3')->shouldReturnAnInstanceOf('Dimsav\Backup\Storage\Drivers\Dropbox');
    }

    function it_makes_a_local_instance()
    {
        $this->beConstructedWith($this->getConfig());
        $this->make('storage_4')->shouldReturnAnInstanceOf('Dimsav\Backup\Storage\Drivers\Local');
    }

    function it_caches_created_instances()
    {
        $this->beConstructedWith($this->getConfig());
        $storage = $this->make('storage_4');
        $this->make('storage_4')->shouldReturn($storage);
    }

    private function getConfig()
    {
        return array(
            'storage_1' => array(),
            'storage_2' => array('driver' => 'false'),
            'storage_3' => array('driver' => 'dropbox', 'username' => 'dropbox@test.com'),
            'storage_4' => array('driver' => 'local', 'destination' => __DIR__),
        );
    }

    function letGo()
    {
        if (is_file($this->tokenFile))
        {
            unlink($this->tokenFile);
        }
    }
}
