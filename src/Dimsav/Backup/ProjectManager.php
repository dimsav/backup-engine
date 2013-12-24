<?php namespace Dimsav\Backup;

use Dimsav\UnixZipper;

class ProjectManager {

    /** @var \Dimsav\Backup\Config $config */
    private $config;

    /** @var \Dimsav\Backup\Logger $log */
    private $log;

    /** @var \Dimsav\Backup\ProjectRepository $repo */
    private $repo;

    public function __construct(Config $config, Logger $log, ProjectRepository $repo)
    {
        $this->config = $config;
        $this->log    = $log;
        $this->repo   = $repo;
    }

    public function backup()
    {
        $this->compressProjectsPaths();
    }

    private function compressProjectsPaths()
    {
        foreach ($this->repo->all() as $project)
        {
            $this->log->info("Compressing project " .$project->getName());
            $compressor = new ProjectCompressor($project, new UnixZipper());
            $compressor->compress();
        }
    }
}