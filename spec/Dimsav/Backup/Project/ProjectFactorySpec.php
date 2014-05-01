<?php

namespace spec\Dimsav\Backup\Project;

use Dimsav\Backup\Element\Element;
use Dimsav\Backup\Element\ElementFactory;
use Dimsav\Backup\Storage\StorageFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ProjectFactorySpec extends ObjectBehavior
{
    function let(ElementFactory $elementFactory, StorageFactory $storageFactory)
    {
        $this->beConstructedWith($this->getConfig(), $elementFactory, $storageFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Dimsav\Backup\Project\ProjectFactory');
    }

    function it_constructs_a_project()
    {
        $project = $this->make('my_project_1');
        $project->shouldHaveType('Dimsav\Backup\Project\Project');
    }

    function it_fills_the_elements_of_a_project(ElementFactory $elementFactory, $elements)
    {
        $elementFactory->makeAll('my_project_1')->shouldBeCalled()->willReturn($elements);

        $project = $this->make('my_project_1');
        $project->getElements()->shouldReturn($elements);
    }

    function it_fills_the_storages_of_a_project(StorageFactory $storageFactory, $storages)
    {
        $storageFactory->makeAll('my_project_1')->shouldBeCalled()->willReturn($storages);

        $project = $this->make('my_project_1');
        $project->getStorages()->shouldReturn($storages);
    }

    function it_returns_all_projects()
    {
        $project1 = $this->make('my_project_1');
        $project2 = $this->make('my_project_2');

        $this->makeAll()->shouldReturn(array('my_project_1' => $project1, 'my_project_2' => $project2));
    }

    private function getConfig()
    {
        return array(
            'projects' => array(
                'my_project_1' => array(),
                'my_project_2' => array(),
            )
        );
    }
}
