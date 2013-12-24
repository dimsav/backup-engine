<?php

class FunctionalTest extends PHPUnit_Framework_TestCase {

    /** @var \Dimsav\Backup\Config */
    private $config;

    public function setUp()
    {
        $this->config = new \Dimsav\Backup\Config('testing');

        $tempDir = dirname($this->config->get('app.log'));
        if (is_dir($tempDir)) exec('rm -rf '.realpath($tempDir));
    }

    public function testAppRunsWithLogs()
    {
        $app = new \Dimsav\Backup\Application($this->config);
        $app->run();

        $this->assertFileExists(realpath($this->config->get('app.log')));
    }

}