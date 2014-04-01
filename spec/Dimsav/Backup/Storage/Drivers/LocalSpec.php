<?php namespace spec\Dimsav\Backup\Storage\Drivers;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LocalSpec extends ObjectBehavior
{
    private $tempDir;

    function let()
    {
        $this->tempDir = __DIR__.'/../../../../../temp';
        if ( ! is_dir($this->tempDir))
        {
            mkdir($this->tempDir);
        }
        $this->tempDir = realpath($this->tempDir);
    }


    // Validation

    function it_throws_exception_if_name_is_not_set()
    {
        $exception = new \InvalidArgumentException('The name for the local storage is not set.');
        $this->shouldThrow($exception)->during('__construct', array(array()));
    }

    function it_throws_exception_if_destination_is_not_set()
    {
        $exception = new \InvalidArgumentException("The local storage 'storage_name' has no destination set.");
        $this->shouldThrow($exception)->during('__construct', array(array('name' => 'storage_name')));
    }

    function it_throws_exception_if_destination_is_not_valid_directory()
    {
        $exception = new \InvalidArgumentException("The destination of the local storage 'storage_name' is not a valid directory.");
        $this->shouldThrow($exception)->during('__construct', array(array('name' => 'storage_name', 'destination' => __DIR__.'/abc')));
    }

    function it_throws_excepetion_if_storing_file_does_not_exist()
    {
        $file = __DIR__.'/test.php';
        $exception = new \InvalidArgumentException("Local storage 'name' could not find the file '$file'.");
        $this->beConstructedWith(array('name'=>'name', 'destination' => $this->tempDir));
        $this->shouldThrow($exception)->duringStore($file);
    }


    // Storage

    function it_stores_the_selected_file_by_copying_the_file_to_the_destination()
    {
        $this->beConstructedWith(array('name'=>'name', 'destination' => $this->tempDir));
        $this->store(__FILE__)->shouldCreateFile($this->tempDir.'/'. basename(__FILE__));
    }

    function letGo()
    {
        exec("rm -rf $this->tempDir");
    }

    function getMatchers()
    {
        return array(
            'createFile' => function($return, $file) {
                return file_exists($file);
            }
        );
    }

}