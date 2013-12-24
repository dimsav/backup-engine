<?php

class FunctionalTest extends PHPUnit_Framework_TestCase {

    /** @var \Dimsav\Backup\Config */
    private $config;

    public function setUp()
    {
        $tempDir = __DIR__.'/../../temp';
        if (is_dir($tempDir)) exec('rm -rf '.realpath($tempDir));

        $this->config = new \Dimsav\Backup\Config('testing');
    }

    public function testAppRunsWithLogs()
    {
        $app = new \Dimsav\Backup\Application($this->config);
        $app->run();

        $this->assertFileExists(realpath($this->config->get('app.log')));

        $file = $this->config->get('app.backups_dir') . '/'
            . date($this->config->get('app.timestamp_prefix'))
            . 'testing-project-1.zip';

        $this->assertFileExists($file);
        $this->assertGreaterThan(0, filesize($file));
    }

}