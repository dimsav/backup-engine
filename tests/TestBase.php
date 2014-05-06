<?php

use Dimsav\Backup\Project;

abstract class TestBase extends PHPUnit_Framework_TestCase {

    protected $backupsDir;

    protected function setUp()
    {
        $this->backupsDir = __DIR__ .'/test_backups';
    }

    protected function runApp($config = null)
    {
        include(__DIR__.'/../backup.php');
    }

    protected function getBaseConfig()
    {
        return array('projects' => array(
            'my_project_1' => array(
                "root_dir" => realpath(__DIR__.'/../src'),
                'storages' => 'test_dir'
                )
            ),
            'storages' => array(
                "test_dir" => array(
                    "driver" => "local",
                    "destination" => $this->backupsDir,
                )
            ),
            'app' => array(
                "timezone" => 'Europe/Berlin',
                "time_limit" => 0,
                "temp_dir" => __DIR__ .'/test_temp',
            )
        );
    }

}