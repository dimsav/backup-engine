<?php namespace Dimsav\Backup\Element\Drivers;

use Dimsav\Backup\Element\AbstractElement;
use Dimsav\Backup\Element\Element;

class Directory extends AbstractElement implements Element {

    private $dir;
    private $root;
    private $excludes = array();

    public function __construct($rootDir, $dir)
    {
        $this->validateRoot($rootDir);
        $this->parseRoot($rootDir);

        $this->parseDir($dir);
        $this->validateDir();
        $this->parseExcludes($dir);
    }

    public function getDir()
    {
        return $this->dir;
    }

    public function getExcludes()
    {
        return $this->excludes;
    }

    private function parseRoot($rootDir)
    {
        $this->root = rtrim($rootDir, '/');
    }

    private function validateRoot($rootDir)
    {
        if ( ! is_dir($rootDir))
        {
            throw new \InvalidArgumentException("The path '$rootDir' is not valid");
        }
        if (substr($rootDir, 0, 1) !== '/')
        {
            throw new \InvalidArgumentException("The path '$rootDir' is not absolute");
        }
    }

    private function parseDir($dirInput)
    {
        if (is_array($dirInput))
        {
            if ( ! isset($dirInput[0]))
            {
                throw new \InvalidArgumentException("The directory is not set.");
            }
            $dir = $dirInput[0];
        }
        else
        {
            $dir = $dirInput;
        }

        $this->dir = $dir == '/' ? $this->root : $this->root .'/' . trim($dir, '/');
    }

    private function validateDir()
    {
        if ( ! is_dir($this->dir))
        {
            throw new \InvalidArgumentException("The path '{$this->dir}' does not exist.");
        }
    }

    private function parseExcludes($input)
    {
        if (is_array($input) && isset($input['excludes']))
        {
            $excludes = (array) $input['excludes'];
            foreach ($excludes as $exclude)
            {
                $this->excludes[] = $this->dir . '/' . trim($exclude, '/');
            }
        }
    }

    /**
     * Saves the files into the extraction directory
     */
    public function extract()
    {

    }

    /**
     * Returns the absolute path of the files created upon extract()
     */
    public function getExtractedFiles()
    {

    }
}
