<?php

namespace spec\Dimsav\Backup\Project;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LocationSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith('/');
        $this->shouldHaveType('Dimsav\Backup\Project\Location');
    }

    function it_normalizes_paths()
    {
        $this->beConstructedWith(__DIR__.'/../Project');
        $this->get()->shouldReturn(__DIR__);
    }


}
