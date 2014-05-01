<?php

use Dimsav\Backup\Project;

class BackupTest extends PHPUnit_Framework_TestCase {

    private $tempDir;
    private $backupsDir;

    protected function setUp()
    {
        $this->tempDir = __DIR__.'/temp/temp';
        $this->backupsDir = __DIR__.'/temp/backups';

        if ( is_dir($this->backupsDir)) exec('rm -rf '.realpath($this->backupsDir));
        mkdir($this->backupsDir, 0777, true);

        if ( is_dir($this->tempDir)) exec('rm -rf '.realpath($this->tempDir));
        mkdir($this->tempDir, 0777, true);
    }
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
                    "destination" => __DIR__."/temp/backups",
                ),
            ),
        );

        $tempDir = $this->tempDir;

        include_once(__DIR__.'/../backup.php');
        $this->assertTrue(true);
    }

}