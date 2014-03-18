<?php

namespace spec\Dimsab\Backup\Project;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LocationSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Dimsab\Backup\Project\Location');
    }
}
