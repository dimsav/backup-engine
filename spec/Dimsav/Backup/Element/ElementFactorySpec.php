<?php

namespace spec\Dimsav\Backup\Element;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Dimsav\Backup\Element\Exceptions\InvalidProjectNameException;

class ElementFactorySpec extends ObjectBehavior
{

    function let()
    {
        $this->beConstructedWith($this->getConfig());
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Dimsav\Backup\Element\ElementFactory');
    }


    // Mysql

    function it_throws_exception_if_required_project_is_not_defined()
    {
        $exception = new InvalidProjectNameException("The project 'invalid_project' was not found.");
        $this->shouldThrow($exception)->duringMakeAll('invalid_project', '', '');
    }

    function it_throws_exception_if_required_driver_is_not_defined()
    {
        $exception = new \InvalidArgumentException("The project 'empty_project' has no driver 'database' set.");
        $this->shouldThrow($exception)->duringMake('empty_project', 'database', '');
    }

    function it_throws_exception_if_required_element_name_is_not_defined()
    {
        $exception = new \InvalidArgumentException("The project 'project_2' has no driver 'database' named 'wrong_name'.");
        $this->shouldThrow($exception)->duringMake('project_2', 'database', 'wrong_name');
    }

    function it_throws_exception_if_required_driver_is_not_supported()
    {
        $exception = new \InvalidArgumentException("The driver 'wrong_name' is not supported. Check your settings in project 'project_1'.");
        $this->shouldThrow($exception)->duringMake('project_1', 'wrong_name', '');
    }

    function it_makes_mysql_elements()
    {
        $this->make('project_2', 'database', 'db_1')->shouldHaveType('Dimsav\Backup\Element\Drivers\Mysql');
    }

    function it_sets_the_database_name_if_missing()
    {
        $this->make('project_2', 'database', 'db_name_2')->shouldHaveType('Dimsav\Backup\Element\Drivers\Mysql');
    }


    // Directories

    function it_throws_exception_if_project_root_is_not_set()
    {
        $exception = new \InvalidArgumentException("The project 'project_1' has no root_dir set. Please set it to backup project files");
        $this->shouldThrow($exception)->duringMake('project_1', 'directories', 0);
    }

    function it_makes_directory_elements()
    {
        $directory = $this->make('project_2', 'directories', 0);
        $directory->shouldHaveType('Dimsav\Backup\Element\Drivers\Directory');
        $directory->getExcludes()->shouldHaveCount(0);
    }

    function it_makes_directory_elements_with_excludes()
    {
        $directory = $this->make('project_2', 'directories', 1);
        $directory->shouldHaveType('Dimsav\Backup\Element\Drivers\Directory');
        $directory->getExcludes()->shouldHaveCount(1);
    }

    function it_makes_directory_with_directory_set_as_array_key()
    {
        $directory = $this->make('project_2', 'directories', 'Element');
        $directory->shouldHaveType('Dimsav\Backup\Element\Drivers\Directory');
        $directory->getExcludes()->shouldHaveCount(1);
    }

    function it_returns_all_the_elements_of_a_project()
    {
        $elements = $this->makeAll('project_2');
        $elements->shouldHaveCount(5);
        $elements->shouldHaveOnlyElementInstances();
    }

    private function getConfig()
    {
        return array(
            'empty_project' => array(),
            'project_1' => array(
                "directories" => array(
                    "/"
                ),
            ),
            'project_2' => array(
                "root_dir" => realpath(__DIR__.'/../'),
                "directories" => array(
                    "Element/Drivers",
                    array("directory" => "Element", 'excludes' => 'Drivers'),
                    "Element" => array('excludes' => 'Drivers'),
                ),
                'database' => array(
                    'db_1' => array(
                        'driver' => 'mysql',
                        'database' => 'db_name_1',
                        'port' => '1',
                        'host' => 'localhost1',
                        'username' => 'username1',
                        'password' => 'password1'
                    ),
                    'db_name_2' => array(
                        'port' => '1',
                        'driver' => 'mysql',
                        'host' => 'localhost1',
                        'username' => 'username1',
                        'password' => 'password1'
                    ),
                ),
            )
        );
    }

    function getMatchers()
    {
        return array(
            'haveOnlyElementInstances' => function($results) {
                $valid = true;
                foreach ($results as $result)
                {
                    $valid = ! is_a($result, 'Dimsav\Backup\Element\Element') ? false : $valid;
                }
                return $valid;
            }
        );
    }
}
