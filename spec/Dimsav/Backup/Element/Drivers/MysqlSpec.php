<?php

namespace spec\Dimsav\Backup\Element\Drivers;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MysqlSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith($this->getValidConfig());
        $this->shouldHaveType('Dimsav\Backup\Element\Element');
        $this->shouldHaveType('Dimsav\Backup\Element\Drivers\Mysql');
        $this->shouldHaveType('Dimsav\Backup\Element\AbstractElement');
    }

    function it_throws_an_error_if_input_is_not_an_array()
    {
        $this->shouldThrow('\PhpSpec\Exception\Example\ErrorException')->during('__construct', array(''));
    }

    function it_throws_an_exception_if_required_fields_are_missing()
    {
        $fields = array('database', 'host', 'port', 'username', 'password');

        foreach ($fields as $field)
        {
            $config = $this->getConfigWithout($field);
            $this->shouldThrow('\InvalidArgumentException')->during('__construct', array($config));
        }
    }


//    function it_extends_abstract_class()
//    {
//        $this->shouldHaveType('\Dimsav\Backup\Project\Element\AbstractElement');
//    }

    // Get extracted

//    function it_returns_an_array_with_the_created_backup_files()
//    {
//
//    }
//
//    // Set/Get extraction dir
//
//    function it_accepts_and_returns_the_extraction_directory()
//    {
//
//    }
//
//    // Extract: Validation
//
//    function it_throws_an_exception_if_the_db_connection_went_wrong()
//    {
//
//    }
//
//    function it_throws_an_exception_if_the_extraction_failed()
//    {
//
//    }
//
//    // Extract
//
//    function it_generates_the_backup_file_in_the_extraction_dir()
//    {
//
//    }


    private function getConfigWithout($field)
    {
        $config = $this->getValidConfig();
        unset($config[$field]);
        return $config;
    }

    private function getValidConfig()
    {
        return array(
            'database' => 'a',
            'host' => 'b',
            'port' => 'c',
            'username' => 'd',
            'password' => 'e',
        );
    }

}
