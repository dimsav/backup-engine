<?php

use Dimsav\Backup\Project;

class BackupTest extends PHPUnit_Framework_TestCase {


    private $backupsDir;

    protected function setUp()
    {
        $this->backupsDir = __DIR__ .'/test_backups';
    }

    /**
     * @test
     */
    public function it_stores_the_backup_of_project_files_locally()
    {
        $config = $this->getBaseConfig();
        $config['projects']['my_project_1']['directories'] = array(
            "Dimsav/Backup/Element" => array('excludes' => array('Exceptions')
            ));

        include(__DIR__.'/../backup.php');

        $file = $this->backupsDir . '/my_project_1/' .
            date("Y-m-d_H-i-s").'_files_dimsav_backup_element.zip';
        $this->assertTrue(is_file($file));
    }

    /**
     * @test
     */
    public function it_stores_the_db_backup_locally()
    {
        $config = $this->getBaseConfig();
        $config['projects']['my_project_1']['mysql'] = array(
            "test_db" => array(
                "host" => "localhost",
                "port" => "3306",
                "username" => "root",
                "password" => "password",
            )
        );
        exec('cd ' . __DIR__ . " && mysql -u root -ppassword test_db < test_db.sql");

        include(__DIR__.'/../backup.php');

        $file = $this->backupsDir . '/my_project_1/' .
            date("Y-m-d_H-i-s").'_test_db.sql';
        $this->assertTrue(is_file($file));
    }

    /**
     * @test
     * @expectedException \Dimsav\Exception\RuntimeException
     */
    public function it_displays_an_error_if_configuration_is_wrong()
    {
        $config = $this->getBaseConfig();
        $config['storages']['dropbox'] = array('driver' => 'dropbox', 'username' => 'test@example.com');
        include(__DIR__.'/../backup.php');
    }

    /**
     * @test
     */
    public function it_includes_the_config()
    {
        $path = __DIR__.'/../config/config.php';

        if ( is_file($path)) {
            $this->assertTrue(false);
        }

        $file = fopen($path, 'w');
        fwrite($file, $this->configFileContents());
        fclose($file);

        include(__DIR__.'/../backup.php');
        $this->assertTrue(isset($config));
        $this->assertTrue(unlink(realpath($path)));
    }

    private function getBaseConfig()
    {
        return array(
            'projects' => array(
                'my_project_1' => array(
                    "root_dir" => realpath(__DIR__.'/../src'),
                    'storages' => 'test_dir'
                )
            ),
            'storages' => array(
                "test_dir" => array(
                    "driver" => "local",
                    "destination" => $this->backupsDir,
                )
            ),
            'app' => array(
                "timezone" => 'Europe/Berlin',
                "time_limit" => 0,
                "temp_dir" => __DIR__ .'/test_temp',
            ),
        );
    }

    private function configFileContents()
    {
        return "<?php return array('projects' => array(), 'storages' => array(), 'app' => array(
                        'timezone' => 'Europe/Berlin',
                        'time_limit' => 0,
                        'temp_dir' => '".__DIR__ ."/test_temp',
                    ),
                );";
    }

    protected function tearDown()
    {
        if (is_dir($this->backupsDir)) exec('rm -rf ' . realpath($this->backupsDir));
    }

}