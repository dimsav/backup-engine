<?php

namespace spec\Dimsav\Backup\Element;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ElementFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Dimsav\Backup\Element\ElementFactory');
    }
}
