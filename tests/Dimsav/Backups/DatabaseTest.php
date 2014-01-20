<?php

use Dimsav\Backup\Project,
    Dimsav\Backup\Config;
use mysql;
class DatabaseTest extends PHPUnit_Framework_TestCase {

    /**
     * @var Config
     */
    private $config;
    /**
     * @var mysqli
     */
    private $c;
    public function setUp()
    {
        $this->config = new Config('testing');
        $project = new Project($this->config, 'test-3');
        $this->c = new mysqli(
            $project->getDbHost(),
            $project->getDbUsername(),
            $project->getDbPassword(),
            $project->getDbName());
    }

    public function testDatabaseDetermination()
    {
        $project = new Project($this->config, 'test-3');
        $this->assertSame($project->getDbName(), "test_3");
    }

    public function testDatabaseConnectionExists()
    {
        $this->assertEquals(0, $this->c->connect_errno);
    }
}