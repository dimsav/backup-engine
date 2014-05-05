<?php namespace Dimsav\Backup\Storage;

use Dimsav\Backup\Storage\Exceptions\InvalidStorageException;

interface Storage {

    /**
     * Validates its properties.
     *
     * @return void
     * @throws InvalidStorageException
     */
    public function validate();

    /**
     * Copies the selected file to the storage system.
     *
     * If the project name is supplied, the file is stored under a
     * directory having the name of the project. Else, the file
     * is stored under the defined destination.
     *
     * @param $sourceFile
     * @param null $projectName
     * @return void
     */
    public function store($sourceFile, $projectName = null);

} 