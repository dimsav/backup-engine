<?php

namespace spec\Dimsav\Backup\Project;

use Dimsav\Backup\Project\Project;
use Dimsav\Backup\Storage\Storage;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Dimsav\Backup\Project\ProjectFactory;
use Dimsav\Backup\Storage\StorageManager;

class ProjectManagerSpec extends ObjectBehavior
{
    function let($factory, $storageManager)
    {
        $factory->beADoubleOf('Dimsav\Backup\Project\ProjectFactory');
        $storageManager->beADoubleOf('Dimsav\Backup\Storage\StorageManager');
        $this->beConstructedWith($factory, $storageManager);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Dimsav\Backup\Project\ProjectManager');
    }

    function it_returns_the_project_names()
    {
        $config = array('projects'=> array('a' => array('storages'=>'s'), 'b' => array('storages'=>'s')));
        $this->setConfig($config);
        $this->getProjectNames()->shouldReturn(array('a', 'b'));
    }

    function it_throws_exception_if_no_projects_are_defined()
    {
        $undefinedException = new \InvalidArgumentException(
            'At least one project must be defined. Check your configuration.');

        $wrongFormatException = new \InvalidArgumentException(
            'The project\'s configuration must be an array.');

        $this->shouldThrow($undefinedException)->duringSetConfig(array());
        $this->shouldThrow($undefinedException)->duringSetConfig(array('projects'));
        $this->shouldThrow($undefinedException)->duringSetConfig(array('projects'=>array()));
        $this->shouldThrow($wrongFormatException)->duringSetConfig(array('projects'=> 'a'));
    }

    function it_throws_exception_if_project_has_no_storages()
    {
        $noStoragesException = new \InvalidArgumentException(
            "The project 'a' has no storages assigned. Check your configuration'");
        $config = array('projects'=>array(
            'a' => array(),
        ));
        $this->shouldThrow($noStoragesException)->duringSetConfig($config);
    }

    function it_returns_all_project_instances($factory, $storageManager,
        Project $projectA, Project $projectB, Storage $storageA, Storage $storageB
    )
    {
        $configA = array('name' => 'a','storages'=>'sa', 'password' => 'ap');
        $configB = array('name' => 'b','storages'=>'sb', 'password' => 'bp');
        $factory->make($configA)->shouldBeCalled()->willReturn($projectA);
        $factory->make($configB)->shouldBeCalled()->willReturn($projectB);
        $storageManager->make('sa')->shouldBeCalled()->willReturn($storageA);
        $storageManager->make('sb')->shouldBeCalled()->willReturn($storageB);
        $projectA->addStorage($storageA)->shouldBeCalled();
        $projectB->addStorage($storageB)->shouldBeCalled();

        $config = array('projects' => array(
            'a' => array('password' => 'ap','storages'=>'sa'),
            'b' => array('password' => 'bp','storages'=>'sb'),
        ));
        $this->setConfig($config);

        $this->getAll()->shouldReturn(array($projectA, $projectB));
    }

}
