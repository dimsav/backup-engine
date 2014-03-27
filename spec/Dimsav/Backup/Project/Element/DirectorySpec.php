<?php namespace spec\Dimsav\Backup\Project;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Dimsav\Backup\Project\Element\Directory;

class DirectorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith('/');
        $this->shouldHaveType('Dimsav\Backup\Project\Element\Directory');
    }

    function it_extends_abstract_class()
    {
        $this->shouldHaveType('\Dimsav\Backup\Project\Element\AbstractElement');
    }

    function it_normalizes_paths()
    {
        $this->beConstructedWith(__DIR__.'/../Project');
        $this->get()->shouldReturn(__DIR__);
    }

    function it_is_initializable_with_base_path()
    {
        $this->beConstructedWith('Project', new Directory(__DIR__.'/../'));
        $this->get()->shouldReturn(realpath(__DIR__));

        $this->beConstructedWith('/Project', new Directory(__DIR__.'/../'));
        $this->get()->shouldReturn(realpath(__DIR__));

        $this->beConstructedWith('Project/', new Directory(__DIR__.'/../'));
        $this->get()->shouldReturn(realpath(__DIR__));

        $this->beConstructedWith('/Project/', new Directory(__DIR__.'/../'));
        $this->get()->shouldReturn(realpath(__DIR__));

        $this->beConstructedWith('Project', new Directory(__DIR__.'/..'));
        $this->get()->shouldReturn(realpath(__DIR__));
    }

    function it_receives_a_path()
    {

    }

    function it_receives_a_base_path()
    {

    }

    function it_receives_an_excludes_list()
    {

    }

    function it_copies_the_path_during_extract()
    {

    }

    function it_returns_the_path_of_the_extracted_path_after_extraction()
    {

    }

    function it_receives_an_extraction_dir()
    {

    }

}
