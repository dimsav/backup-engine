<?php

use Dimsav\Backup\Project;

class BackupsTest extends PHPUnit_Framework_TestCase {

    private $config;

    public function setUp()
    {
        $this->config = new \Dimsav\Backup\Config('testing');
    }

    public function testConfigDetermination()
    {
        $this->assertSame(
            $this->config->get('projects.default.password'),
            'testing-default-secret');
    }

    public function testProjectDeterminationFromConfig()
    {
        $project = new Project($this->config, 'testing-project-1');

        $this->assertSame($project->getName(), "testing-project-1");
        $this->assertSame(
            $project->getPaths(),
            array(
                "/absolute/project/folder/path",
                "/absolute/project/file/text.txt",
            )
        );
        $this->assertSame(
            $project->getExcludes(),
            array(
                "/absolute/project/folder/path/cache",
                "/absolute/project/folder/path/logs",
            )
        );
        $this->assertSame($project->getDbName(), "testing-db-name");

    }

    public function testProjectDefaults()
    {
        $project = new Project($this->config, 'testing-project-1');

        $this->assertSame($project->getDbHost(), "localhost");
        $this->assertSame($project->getDbPort(), "3306");
        $this->assertSame($project->getDbUsername(), "db-user");
        $this->assertSame($project->getDbPassword(), "db-pass");
    }

    public function testProjectOverridingDefaults()
    {
        $project = new Project($this->config, 'testing-project-1');
        $this->assertSame($project->getPassword(), null);
    }
}