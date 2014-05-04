<?php

use Dimsav\Backup\Project;

class BackupTest extends PHPUnit_Framework_TestCase {

    /**
     * @test
     */
    public function it_stores_the_backup_files_locally()
    {
        $config = array(
            'projects' => array(
                'my_project_1' => array(
                    "root_dir" => realpath(__DIR__.'/../src'),

                    "directories" => array(
                        "Dimsav/Backup/Element" => array('excludes' => array('Exceptions')),
                    ),
                    'storages' => 'temp_dir'
                )
            ),
            'storages' => array(
                "temp_dir" => array(
                    "driver" => "local",
                    "destination" => __DIR__."/test_backups",
                ),
            ),
            'app' => array(
                "timezone" => 'Europe/Berlin',
                "time_limit" => 0,
                "temp_dir" => __DIR__ .'/test_temp',
            )
        );

        include_once(__DIR__.'/../backup.php');
        $this->assertTrue(true);
    }

}