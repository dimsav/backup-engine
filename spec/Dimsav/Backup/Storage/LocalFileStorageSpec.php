<?php

namespace spec\Dimsav\Backup\Storage;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LocalFileStorageSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith($this->getData());
    }

    private function getData()
    {
        return array(
            'alias' => 'a',
            'destination' => 'd',
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Dimsav\Backup\Storage\LocalFileStorage');
    }

    function it_returns_driver()
    {
        $this->getDriver()->shouldReturn('local_file');
    }
}
