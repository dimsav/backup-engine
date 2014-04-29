<?php namespace spec\Dimsav\Backup\Project;

use Dimsav\Backup\Element\Element;
use Dimsav\Backup\Project\Project;
use Dimsav\Backup\Storage\Storage;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @mixin Project
 */
class ProjectSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Dimsav\Backup\Project\Project');
    }

    function it_receives_and_returns_storages(Storage $storage1, Storage $storage2)
    {
        $this->addStorage($storage1);
        $this->addStorage($storage2);
        $this->getStorages()->shouldReturn(array($storage1, $storage2));
    }

    function it_receives_and_returns_elements(Element $element)
    {
        $this->getElements()->shouldReturn(array());
        $this->addElement($element);
        $this->getElements()->shouldReturn(array($element));
    }

    function it_receives_and_returns_a_name($name)
    {
        $this->setName($name);
        $this->getName()->shouldReturn($name);
    }

}
