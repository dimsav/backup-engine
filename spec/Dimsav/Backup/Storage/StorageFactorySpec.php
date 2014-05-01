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

    function it_throws_exception_if_storages_are_not_set()
    {
        $this->shouldThrow('Dimsav\Backup\Storage\Exceptions\StoragesNotConfiguredException')
            ->during('__construct', array(array()));
    }

    function it_throws_exception_if_storage_name_not_set()
    {
        $this->beConstructedWith(array('storages' => array()));
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

    function it_throws_exception_if_project_has_no_project_defined()
    {
        $this->beConstructedWith($this->getConfig());
        $this->shouldThrow('Dimsav\Backup\Storage\Exceptions\StoragesNotConfiguredException')->duringMakeAll('my_project_1');
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

    function it_returns_all_the_storages_of_a_project()
    {
        $this->beConstructedWith($this->getConfig());

        $storage3 = $this->make('storage_3');
        $storage4 = $this->make('storage_4');

        $storages = $this->makeAll('my_project_2');
        $storages->shouldReturn(array($storage3, $storage4));
        $storages->shouldHaveOnlyStorageInstances();
    }

    function getMatchers()
    {
        return array(
            'haveOnlyStorageInstances' => function($results) {
                $valid = true;
                foreach ($results as $result)
                {
                    $valid = ! is_a($result, 'Dimsav\Backup\Storage\Storage') ? false : $valid;
                }
                return $valid;
            }
        );
    }

    private function getConfig()
    {
        return array(
            'projects' => array(
                'my_project_1' => array(),
                'my_project_2' => array('storages' => array('storage_3', 'storage_4')),
            ),
            'storages' => array (
                'storage_1' => array(),
                'storage_2' => array('driver' => 'false'),
                'storage_3' => array('driver' => 'dropbox', 'username' => 'dropbox@test.com'),
                'storage_4' => array('driver' => 'local', 'destination' => __DIR__),
            )
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
