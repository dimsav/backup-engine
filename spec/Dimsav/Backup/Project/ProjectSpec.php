<?php namespace spec\Dimsav\Backup\Project;

use Dimsav\Backup\Project\Element\Database;
use Dimsav\Backup\Project\Location;
use Dimsav\Backup\Storage\StorageInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ProjectSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('projectName');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Dimsav\Backup\Project\Project');
        $this->getName()->shouldReturn('projectName');
    }

    function it_receives_and_returns_databases(Database $database)
    {
        $this->addDatabase($database);
        $this->getDatabases()->shouldReturn(array($database));
    }

    function it_receives_and_returns_paths(Location $path)
    {
        $this->addPath($path);
        $this->getPaths()->shouldReturn(array($path));
    }

    function it_receives_and_returns_excludes(Location $exclude)
    {
        $this->addExclude($exclude);
        $this->getExcludes()->shouldReturn(array($exclude));
    }

    function it_receives_and_returns_password()
    {
        $this->setPassword('pass');
        $this->getPassword()->shouldReturn('pass');
    }

    function it_receives_and_returns_storages(StorageInterface $storage1, StorageInterface $storage2)
    {
        $storage1->getAlias()->shouldBeCalled()->willReturn('storage1');
        $storage2->getAlias()->shouldBeCalled()->willReturn('storage2');

        $this->addStorage($storage1);
        $this->addStorage($storage2);
        $this->getStorages()->shouldReturn(array('storage1' => $storage1, 'storage2' => $storage2));

    }

    function it_receives_and_returns_storage_names()
    {
        $this->setStorageNames($names = array('a', 'b'));
        $this->getStorageNames()->shouldReturn($names);
    }

}
