<?php namespace Dimsav\Backup;

class Shell
{
    private $lastLine;
    private $output;
    private $statusCode;

    public function exec($command)
    {
        $this->lastLine = \exec($command, $this->output, $this->statusCode);
    }

    public function getLastLine()
    {
        return $this->lastLine;
    }

    public function getOutput()
    {
        return $this->output;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
