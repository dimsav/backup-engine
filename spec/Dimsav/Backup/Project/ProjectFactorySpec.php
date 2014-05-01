<?php

namespace spec\Dimsav\Backup\Project;

use Dimsav\Backup\Element\Element;
use Dimsav\Backup\Element\ElementFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ProjectFactorySpec extends ObjectBehavior
{
    function let(ElementFactory $elementFactory)
    {
        $this->beConstructedWith($this->getConfig(), $elementFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Dimsav\Backup\Project\ProjectFactory');
    }

    function it_constructs_a_project()
    {
        $project = $this->make('my_project');
        $project->shouldHaveType('Dimsav\Backup\Project\Project');
    }

    function it_fills_the_elements_of_a_project(ElementFactory $elementFactory, $elements)
    {
        $elementFactory->makeAll('my_project')->shouldBeCalled()->willReturn($elements);

        $project = $this->make('my_project');
        $project->getElements()->shouldReturn($elements);
    }

    function it_fills_the_storages_of_a_project()
    {
//        $project = $this->make('my_project');
//        $project->getStorages()->shouldReturn($storages);
//        $project->getStorages()->shouldReturn('...');
    }

    private function getConfig()
    {
        return array(
            'projects' => array(
                'my_project' => array(
                ),
            )
        );
    }
}
