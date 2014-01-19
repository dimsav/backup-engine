<?php namespace Dimsav\Backup;

use Dimsav\UnixZipper;


class ProjectCompressor {

    /** @var  \Dimsav\UnixZipper */
    private $zipper;

    public function __construct(Project $project, UnixZipper $zipper)
    {
        $this->zipper  = $zipper;
        $this->project = $project;
    }

    public function compress()
    {
        $this->addPaths();
        $this->addExcludes();
        $this->addPassword();

        $this->zipper->setDestination($this->project->getBackupFile('zip'));
        $this->zipper->compress();
        $this->project->addToGeneratedFiles($this->zipper->getFiles());
    }

    private function addPaths()
    {
        foreach ($this->project->getPaths() as $path)
        {
            $this->zipper->add($path);
        }
    }

    private function addExcludes()
    {
        foreach ($this->project->getExcludes() as $exclude)
        {
            $this->zipper->exclude($exclude);
        }
    }

    private function addPassword()
    {
        if ($this->project->getPassword())
        {
            $this->zipper->setPassword($this->project->getPassword());
        }
    }
}