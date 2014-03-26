<?php namespace spec\Dimsav\Backup\Project\Element;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DatabaseSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Dimsav\Backup\Project\Element\Database');
    }

    function it_should_be_fillable()
    {
        $data = array(
            'name' => 'a',
            'host' => 'b',
            'port' => 'c',
            'username' => 'd',
            'password' => 'e',
        );
        $this->fill($data);

        $this->getName()->shouldBe('a');
        $this->getHost()->shouldBe('b');
        $this->getPort()->shouldBe('c');
        $this->getUsername()->shouldBe('d');
        $this->getPassword()->shouldBe('e');
    }

    function it_extends_abstract_class()
    {
        $this->shouldHaveType('\Dimsav\Backup\Project\Element\AbstractElement');
    }

    // Get extracted

    function it_returns_an_array_with_the_created_backup_files()
    {

    }

    // Set/Get extraction dir

    function it_accepts_and_returns_the_extraction_directory()
    {

    }

    // Extract: Validation

    function it_throws_an_exception_if_required_fields_are_missing_upon_extracting()
    {

    }

    function it_throws_an_exception_if_the_db_connection_went_wrong()
    {

    }

    function it_throws_an_exception_if_the_extraction_failed()
    {

    }

    // Extract

    function it_generates_the_backup_file_in_the_extraction_dir()
    {

    }

}
