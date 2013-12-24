<?php

use Dimsav\Backup\Project;
use Dimsav\Backup\ProjectRepository;

class BackupsTest extends PHPUnit_Framework_TestCase {

    /** @var \Dimsav\Backup\Config */
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
                realpath(__DIR__."/../../../src"),
            )
        );
        $this->assertSame(
            $project->getExcludes(),
            array(
                realpath(__DIR__."/../../../src/Dimsav/Backup/Config.php"),
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

    public function testGetAllProjects()
    {
        $repo = new ProjectRepository($this->config);
        $projects = $repo->all();

        $this->assertGreaterThanOrEqual(1, count($projects));
        $this->assertInstanceOf('Dimsav\Backup\Project', $projects[0]);
    }
}