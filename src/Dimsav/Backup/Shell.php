<?php namespace Dimsav\Backup;

class Shell
{
    /**
     * @var string
     */
    private $lastLine;

    /**
     * @var string
     */
    private $output;

    /**
     * @var int
     */
    private $statusCode;

    /**
     * @param string $command
     */
    public function exec($command)
    {
        $this->lastLine = \exec($command, $this->output, $this->statusCode);
    }

    /**
     * @return string
     */
    public function getLastLine()
    {
        return $this->lastLine;
    }

    /**
     * @return string
     */
    public function getOutput()
    {
        return implode('', $this->output);
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
