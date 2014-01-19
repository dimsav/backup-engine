<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use Dimsav\Backup\Application;

require_once __DIR__.'/../../../vendor/autoload.php';
require_once __DIR__.'/../../../vendor/phpunit/phpunit/PHPUnit/Framework/Assert/Functions.php';

/**
 * Features context.
 */
class FeatureContext extends BehatContext
{

    private $configHelper;
    private $app;

    public function __construct(array $parameters)
    {
        $this->configHelper = new \Dimsav\Backups\TestConfigHelper();
        $this->app = new Application($this->configHelper->getConfig());
    }

    /**
     * @BeforeSuite
     */
    public static function prepare()
    {
        $tempDir = __DIR__.'/../../temp';
        if (is_dir($tempDir)) exec('rm -rf '.realpath($tempDir));
    }

    /**
     * @Given /^I run the app using the project "([^"]*)"$/
     */
    public function iRunTheAppUsingTheProject($projectName)
    {
        $this->configHelper->excludeProjectsNotMatching($projectName);
        $this->app->run();
    }

    /**
     * @Then /^I should see a folder named "([^"]*)" in the backups directory$/
     */
    public function iShouldSeeAFolderNamedInTheBackupsDirectory($folderName)
    {
        assertTrue(is_dir($this->configHelper->getBackupDir($folderName)));
    }

    /**
     * @Given /^The backup folder of project "([^"]*)" should contain (\d+) file of type "([^"]*)"$/
     */
    public function theBackupFolderOfProjectShouldContainFileOfType($projectName, $count, $fileType)
    {
        $files = scandir($this->configHelper->getBackupDir($projectName));
        $found = 0;
        foreach ($files as $file)
        {
            if (pathinfo($file, PATHINFO_EXTENSION) == $fileType)
            {
                $found ++;
            }
        }

        assertEquals($count, $found);
    }
}
