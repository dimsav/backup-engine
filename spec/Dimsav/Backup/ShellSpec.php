<?php

namespace spec\Dimsav\Backup;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ShellSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Dimsav\Backup\Shell');
    }

    function it_has_exec_method()
    {
        $iam = exec('whoami');
        $this->exec('whoami');
        $this->getLastLine()->shouldReturn($iam);
        $this->getOutput()->shouldBe(array($iam));
        $this->getStatusCode()->shouldBe(0);
    }

}
