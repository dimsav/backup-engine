<?php namespace spec\Dimsav\Backup\Storage\Drivers;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DropboxSpec extends ObjectBehavior
{

    // Validation

    function it_throws_exception_if_name_not_set()
    {
        $exception = new \InvalidArgumentException("The name for the 'dropbox' storage is not set.");
        $this->shouldThrow($exception)->during('__construct', array(array()));
    }

    function it_throws_exception_if_username_or_password_not_set()
    {
        $exception = new \InvalidArgumentException("The local storage 'storage_name' has no username set.");
        $this->shouldThrow($exception)->during('__construct', array(array('name' => 'storage_name')));

        $exception = new \InvalidArgumentException("The local storage 'storage_name' has no password set.");
        $this->shouldThrow($exception)->during('__construct', array(array('name' => 'storage_name', 'username' => 'u')));
    }

    function it_throws_excepetion_if_storing_file_does_not_exist()
    {
        $file = __DIR__.'/test.php';
        $exception = new \InvalidArgumentException("Dropbox storage 'name' could not find the file '$file'.");
        $this->beConstructedWith($this->getConfig());
        $this->shouldThrow($exception)->duringStore($file);
    }

    // Storage

//    function it_uploads_the_file_to_dropbox()
//    {
//
//    }

//    function it_expects_during_storage_the_destination_to_be_the_root_if_not_specified()
//    {
//
//    }

    private function getConfig()
    {
        return array(
            'name' => 'name',
            'username' => 'username',
            'password' => 'password'
        );
    }
}
