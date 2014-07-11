<?php

namespace spec\Dimsav\Backup\Utilities;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DefaultsParserSpec extends ObjectBehavior
{
    function it_merges_the_storages_defaults_into_the_projects()
    {
        $this->beConstructedWith($this->getConfig());
        $this->parse()->shouldReturn($this->getParsed());
    }

    function it_returns_the_full_config_if_no_defaults_are_set()
    {
        $config = array('projects' => array());
        $this->beConstructedWith($config);
        $this->parse()->shouldReturn($config);
    }

    private function getConfig()
    {
        return array(
            'project_defaults' => array(
                "database" => array(
                    "driver" => "mysql",
                    "host" => "localhost",
                    "port" => "3306",
                    "username" => "user",
                    "password" => "pass"
                ),
                "storages" => "dropbox_1"
            ),
            'projects' => array(
                'my_project_1' => array(
                    'database' => array(
                        "database_name_1" => array(
                            'driver' => 'mysql',
                            'username' => 'user1',
                            'password' => 'pass1',
                        )
                    )
                ),
                'my_project_2' => array(
                    'database' => array(
                        "database_name_2" => array()
                    ),
                    'storages' => array('dropbox_2')
                )
            )
        );
    }

    private function getParsed()
    {
        return array(
            'projects' => array(
                'my_project_1' => array(
                    'database' => array(
                        "database_name_1" => array(
                            "driver" => 'mysql',
                            "username" => 'user1',
                            'password' => 'pass1',
                            "host" => "localhost",
                            "port" => "3306"
                        )
                    ),
                    'storages' => "dropbox_1",
                ),
                'my_project_2' => array(
                    'database' => array(
                        "database_name_2" => array(
                            "driver" => 'mysql',
                            "username" => 'user',
                            'password' => 'pass',
                            "host" => "localhost",
                            "port" => "3306"
                        ),
                    ),
                    'storages' => array('dropbox_2'),
                ),
            )
        );
    }
}
