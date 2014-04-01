<?php namespace spec\Dimsav\Backup\Storage\Drivers;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LocalSpec extends ObjectBehavior
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
        $this->shouldHaveType('Dimsav\Backup\Storage\Drivers\Local');
    }

}
