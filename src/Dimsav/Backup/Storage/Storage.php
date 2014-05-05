<?php namespace Dimsav\Backup\Storage;

interface Storage {

    /**
     * Validates its properties.
     *
     * @return mixed
     */
    public function validate();

    /**
     * Copies the selected file to the storage system
     *
     * @param $sourceFile
     * @param null $projectName
     * @return mixed
     */
    public function store($sourceFile, $projectName = null);

} 