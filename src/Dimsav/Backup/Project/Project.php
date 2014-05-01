<?php namespace Dimsav\Backup\Project;

use Dimsav\Backup\Element\Element;
use Dimsav\Backup\Storage\Storage;

class Project {

    private $name;

    /**
     * @var Storage[]
     */
    private $storages = array();

    /**
     * @var Element[]
     */
    private $elements = array();

    public function setStorages($storages)
    {
        $this->storages = $storages;
    }

    public function getStorages()
    {
        return $this->storages;
    }

    public function setElements($elements)
    {
        $this->elements = $elements;
    }

    public function getElements()
    {
        return $this->elements;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function backup($tempDir)
    {
        $this->validateTempDir($tempDir);
        foreach ($this->elements as $element)
        {
            $element->setExtractionDir($tempDir);
            $element->extract();
            $this->storeFiles($element->getExtractedFiles());
        }
    }

    private function validateTempDir($tempDir)
    {
        if ( ! is_dir($tempDir))
        {
            throw new \InvalidArgumentException(
                "The temp directory '$tempDir' is not valid.".
                " Make sure this path is writable.");
        }
    }

    private function storeFiles($extractedFiles)
    {
        foreach ($extractedFiles as $extractedFile) {
            $this->storeFile($extractedFile);
        }
    }

    private function storeFile($file)
    {
        foreach($this->storages as $storage)
        {
            $storage->store($file, $this->name);
        }
    }
}
