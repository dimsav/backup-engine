<?php namespace Dimsav\Backup\Storage;

abstract class AbstractStorage {

    protected $alias;
    protected $destination;

    public function getDestination()
    {
        return $this->destination;
    }

    public function getType()
    {
        return static::TYPE;
    }

    public function getAlias()
    {
        return $this->alias;
    }

} 