<?php

namespace spec\Dimsav\Backup\Element\Drivers;

use Dimsav\Backup\Shell;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MysqlSpec extends ObjectBehavior
{
    function it_is_initializable(Shell $shell)
    {
        $this->beConstructedWith($this->getValidConfig(), $shell);
        $this->shouldHaveType('Dimsav\Backup\Element\Element');
        $this->shouldHaveType('Dimsav\Backup\Element\Drivers\Mysql');
        $this->shouldHaveType('Dimsav\Backup\Element\AbstractElement');
    }

    function it_throws_an_error_if_input_is_not_an_array(Shell $shell)
    {
        $this->shouldThrow('\PhpSpec\Exception\Example\ErrorException')->during('__construct', array('', $shell));
    }

    function it_throws_an_exception_if_required_fields_are_missing(Shell $shell)
    {
        $fields = array('database', 'host', 'port', 'username', 'password');

        foreach ($fields as $field)
        {
            $config = $this->getConfigWithout($field);
            $this->shouldThrow('\InvalidArgumentException')->during('__construct', array($config, $shell));
        }
    }

    // Get extracted

    function it_dumps_the_sql_file(Shell $shell)
    {
        $this->beConstructedWith($this->getValidConfig(), $shell);

        $this->getExtractedFiles()->shouldHaveCount(0);

        $this->setExtractionDir(__DIR__);
        $shell->getStatusCode()->shouldBeCalled()->willReturn(0);
        $shell->exec($this->getCommand())->shouldBeCalled();
        $this->extract();
        $this->getExtractedFiles()->shouldReturn(array(__DIR__.'/dbname.sql'));
    }

    // Extract: Validation

    function it_throws_an_exception_if_the_extraction_failed(Shell $shell)
    {
        $shell->getStatusCode()->shouldBeCalled()->willReturn(1);
        $shell->getOutput()->shouldBeCalled()->willReturn('');
        $shell->exec($this->getCommand())->shouldBeCalled();

        $this->beConstructedWith($this->getValidConfig(), $shell);
        $this->setExtractionDir(__DIR__);
        $this->shouldThrow('Dimsav\Backup\Element\Drivers\ExtractionFailureException')->duringExtract();
    }


    private function getConfigWithout($field)
    {
        $config = $this->getValidConfig();
        unset($config[$field]);
        return $config;
    }

    private function getValidConfig()
    {
        return array(
            'database' => 'dbname',
            'host' => 'localhost',
            'port' => '123',
            'username' => 'username',
            'password' => 'password',
        );
    }

    /**
     * @return string
     */
    private function getCommand()
    {
        return "mysqldump --host='localhost' --port='123' --user='username'".
               " --password='password' 'dbname' > '" . __DIR__ . "/dbname.sql'";
    }

}
