<?php namespace Dimsav\Backup;

class ApplicationTest extends \TestBase {

    /**
     * @test
     */
    function it_deletes_the_temp_dir_while_shutting_down()
    {
        $config = $this->getBaseConfig();

        include(__DIR__.'/../../../example/backup.php');

        $this->assertFileExists($config['app']['temp_dir']);
        $app->shutDown();
        $this->assertFileNotExists($config['app']['temp_dir']);
    }

} 