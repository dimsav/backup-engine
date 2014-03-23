<?php

namespace spec\Dimsav\Backup\Project;

use Dimsav\Backup\Project\Project;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Dimsav\Backup\Project\ProjectFactory;

class ProjectManagerSpec extends ObjectBehavior
{
    function let($factory)
    {
        $factory->beADoubleOf('Dimsav\Backup\Project\ProjectFactory');
        $this->beConstructedWith($factory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Dimsav\Backup\Project\ProjectManager');
    }

    function it_returns_the_project_names()
    {
        $config = array('projects'=> array('a' => array(), 'b' => array()));
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

    function it_returns_all_project_instances($factory, Project $projectA, Project $projectB)
    {
        $config = array('projects' => array(
            'a' => array('password' => 'ap'),
            'b' => array('password' => 'bp'),
        ));

        $configA = array('name' => 'a', 'password' => 'ap');
        $configB = array('name' => 'b', 'password' => 'bp');

        $this->setConfig($config);

        $factory->make($configA)->shouldBeCalled()->willReturn($projectA);
        $factory->make($configB)->shouldBeCalled()->willReturn($projectB);

        $this->getAll()->shouldReturn(array($projectA, $projectB));
    }

}
