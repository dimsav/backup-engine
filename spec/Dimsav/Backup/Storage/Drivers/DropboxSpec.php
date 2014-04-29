<?php namespace spec\Dimsav\Backup\Storage\Drivers;

use Dimsav\Backup\Shell;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DropboxSpec extends ObjectBehavior
{
    private $tokenFile;
    private $tokenDir;

    function let()
    {
        $this->tokenDir = __DIR__.'/../../../../../config/tokens';
        $this->tokenFile = $this->tokenDir.'/.dropbox_name';
        touch($this->tokenFile);
    }

    // Validation

    function it_throws_exception_if_name_not_set(Shell $shell)
    {
        $exception = new \InvalidArgumentException("The name for the 'dropbox' storage is not set.");
        $this->shouldThrow($exception)->during('__construct', array(array(), $shell));
    }

    function it_throws_exception_if_username_not_set(Shell $shell)
    {
        $exception = new \InvalidArgumentException("The local storage 'storage_name' has no username set.");
        $this->shouldThrow($exception)->during('__construct', array(array('name' => 'storage_name'), $shell));
    }

    function it_throws_excepetion_if_storing_receives_an_invalid_path(Shell $shell)
    {
        $this->makeToken();
        $file = __DIR__.'/test.php';
        $exception = new \InvalidArgumentException("Dropbox storage 'name' could not find the file '$file'.");
        $this->beConstructedWith($this->getConfig(), $shell);
        $this->shouldThrow($exception)->duringStore($file);
    }

    function it_throws_exception_if_dropbox_token_is_not_set(Shell $shell)
    {
        $this->clearToken();
        $exception = new \InvalidArgumentException("The dropbox storage 'name' has not a token set.");
        $this->shouldThrow($exception)->during('__construct', array($this->getConfig(), $shell));
    }

    // Storage

    function it_uploads_the_file_to_dropbox(Shell $shell)
    {
        $this->makeToken();
        $command = $this->uploaderPath() . $this->getScriptConfig() . ' upload ' . __FILE__ . ' /';

        $shell->exec($command)->shouldBeCalled();
        $this->beConstructedWith($this->getConfig(), $shell);
        $this->store(__FILE__);
    }

    function it_uploads_the_file_to_dropbox_to_the_selected_destination(Shell $shell)
    {
        $this->makeToken();
        $command = $this->uploaderPath() . $this->getScriptConfig() . ' upload ' . __FILE__ . ' /Backups/project_name';

        $config = $this->getConfig();
        $config['destination'] = '/Backups';

        $shell->exec($command)->shouldBeCalled();
        $this->beConstructedWith($config, $shell);
        $this->store(__FILE__, 'project_name');
    }

    private function uploaderPath()
    {
        return realpath(__DIR__.'/../../../../../bin/dropbox_uploader.sh');
    }

    private function getScriptConfig()
    {
        return " -f ". realpath(__DIR__.'/../../../../../config').'/tokens/.dropbox_name';
    }

    private function makeToken($name = 'name')
    {
        $this->tokenFile = $this->tokenDir.'/.dropbox_'.$name;
        touch($this->tokenFile);
    }

    private function getConfig()
    {
        return array(
            'name' => 'name',
            'username' => 'username',
            'password' => 'password'
        );
    }

    private function clearToken()
    {
        if (is_file($this->tokenFile))
        {
            unlink($this->tokenFile);
        }
    }

    function letGo()
    {
        $this->clearToken();
    }

    function getMatchers()
    {
        return array(
            'createTokensDir' => function() {
                return is_dir(__DIR__.'/../../../../../config/tokens');
            }
        );
    }

}
