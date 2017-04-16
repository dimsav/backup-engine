<?php namespace Dimsav\Backup\Element\Drivers;

use Dimsav\Backup\Element\AbstractElement;
use Dimsav\Backup\Element\Element;
use Dimsav\UnixZipper;

class Directory extends AbstractElement implements Element {

    private $dir;
    private $root;
    private $excludes = array();
    /**
     * @var \Dimsav\UnixZipper
     */
    private $zipper;
    private $destinationFile;

    public function __construct($rootDir, $config, UnixZipper $zipper)
    {
        $this->validateRoot($rootDir);
        $this->parseRoot($rootDir);

        $this->parseDir($config);
        $this->validateDir();
        $this->parseExcludes($config);
        $this->zipper = $zipper;
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
            if ( ! isset($dirInput["directory"]))
            {
                throw new \InvalidArgumentException("There is no backup directory set for this configuration");
            }
            $dir = $dirInput["directory"];
        }
        else
        {
            $dir = $dirInput;
        }
        $this->parseDestination($dir);
        $this->dir = $dir == '/' ? $this->root : $this->root .'/' . trim($dir, '/');
    }

    private function parseDestination($dir)
    {
        $dir = trim($dir, '/');
        $dir = str_replace('/', '_', $dir);
        $dir = strtolower($dir);
        $dir = $dir ? 'files_' . $dir : 'files';
        $this->destinationFile = $dir . '.zip';
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
    public function backup()
    {

        $this->zipper->add($this->dir);
        $this->extractedFiles[] = $file = date("Y-m-d_H-i-s") . '_'.$this->destinationFile;
        $this->zipper->setDestination($this->extractionDir . '/'.$file);
        foreach ($this->excludes as $exclude)
        {
            $this->zipper->exclude($exclude);
        }
        $this->zipper->compress();
    }

}
