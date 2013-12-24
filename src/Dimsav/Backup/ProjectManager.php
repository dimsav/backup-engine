<?php namespace Dimsav\Backup;

class ProjectManager {

    /** @var LoggerSingleton  */
    private $log;
    private $compressor;

    public function __construct(ProjectCompressor $compressor)
    {
        $this->compressor = $compressor;
        $this->log = LoggerSingleton::getInstance();
    }

    public function compressProjectsFiles()
    {
        $this->log->addInfo("Compressing project's files");

        /** @var Project $project */
        foreach ($this->getProjects() as $project)
        {
            $project->compressFiles();
        }
    }
}