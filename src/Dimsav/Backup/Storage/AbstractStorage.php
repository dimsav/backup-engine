<?php namespace Dimsav\Backup\Storage;

abstract class AbstractStorage {

    protected $alias;
    protected $destination;

    public function __construct(array $data)
    {
        $this->alias = isset($data['alias']) ? $data['alias'] : null;
        $this->destination = isset($data['destination']) ? $data['destination'] : null;
    }

    public function getDestination()
    {
        return $this->destination;
    }

    public function getDriver()
    {
        return static::DRIVER;
    }

    public function getAlias()
    {
        return $this->alias;
    }

} 