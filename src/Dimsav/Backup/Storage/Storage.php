<?php namespace Dimsav\Backup\Storage;

interface Storage {

    /**
     * Copies the selected file to the storage system
     *
     * @param $sourceFile
     * @param null $projectName
     * @return mixed
     */
    public function store($sourceFile, $projectName = null);

} 