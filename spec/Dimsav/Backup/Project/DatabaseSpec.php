<?php namespace spec\Dimsav\Backup\Project;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DatabaseSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Dimsav\Backup\Project\Database');
    }

    function it_should_be_fillable()
    {
        $data = array(
            'name' => 'a',
            'host' => 'b',
            'port' => 'c',
            'username' => 'd',
            'password' => 'e',
        );
        $this->fill($data);

        $this->getName()->shouldBe('a');
        $this->getHost()->shouldBe('b');
        $this->getPort()->shouldBe('c');
        $this->getUsername()->shouldBe('d');
        $this->getPassword()->shouldBe('e');
    }


}
