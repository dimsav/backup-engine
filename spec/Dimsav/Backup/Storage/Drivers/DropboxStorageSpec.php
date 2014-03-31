<?php namespace spec\Dimsav\Backup\Storage\Drivers;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DropboxSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith($this->getData());
    }

    private function getData()
    {
        return array(
            'alias' => 'a',
            'username' => 'u',
            'password' => 'p',
            'destination' => 'd',
        );
    }

    function it_returns_the_username()
    {
        $this->getUsername()->shouldReturn('u');
    }

    function it_returns_the_password()
    {
        $this->getPassword()->shouldReturn('p');
    }

    function it_returns_the_destination()
    {
        $this->getDestination()->shouldReturn('d');
    }

    function it_returns_the_alias()
    {
        $this->getAlias()->shouldReturn('a');
    }

}
