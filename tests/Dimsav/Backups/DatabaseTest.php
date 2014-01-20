<?php

use Dimsav\Backup\Project;

class DatabaseTest extends PHPUnit_Framework_TestCase {

    /** @var \Dimsav\Backup\Config */
    private $config;

    public function setUp()
    {
        $this->config = new \Dimsav\Backup\Config('testing');
    }

    public function testDatabaseDetermination()
    {
        $project = new Project($this->config, 'test-3');
        $this->assertSame($project->getDbName(), "test_3");
    }
}