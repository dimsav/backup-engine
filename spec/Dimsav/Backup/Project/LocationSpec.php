<?php namespace spec\Dimsav\Backup\Project;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Dimsav\Backup\Project\Location;

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

    function it_is_initializable_with_base_path()
    {
        $this->beConstructedWith('Project', new Location(__DIR__.'/../'));
        $this->get()->shouldReturn(realpath(__DIR__));

        $this->beConstructedWith('/Project', new Location(__DIR__.'/../'));
        $this->get()->shouldReturn(realpath(__DIR__));

        $this->beConstructedWith('Project/', new Location(__DIR__.'/../'));
        $this->get()->shouldReturn(realpath(__DIR__));

        $this->beConstructedWith('/Project/', new Location(__DIR__.'/../'));
        $this->get()->shouldReturn(realpath(__DIR__));

        $this->beConstructedWith('Project', new Location(__DIR__.'/..'));
        $this->get()->shouldReturn(realpath(__DIR__));
    }

}
