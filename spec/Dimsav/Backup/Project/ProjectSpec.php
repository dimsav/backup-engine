<?php

namespace spec\Dimsav\Backup\Project;

use Dimsav\Backup\Project\Database;
use Dimsav\Backup\Project\Location;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ProjectSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('projectName');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Dimsav\Backup\Project\Project');
        $this->getName()->shouldReturn('projectName');
    }

    function it_receives_and_returns_databases(Database $database)
    {
        $this->addDatabase($database);
        $this->getDatabases()->shouldReturn(array($database));
    }

    function it_receives_and_returns_a_base_path(Location $path)
    {
        $this->setBasePath($path);
        $this->getBasePath()->shouldReturn($path);
    }

    function it_receives_and_returns_paths(Location $path)
    {
        $this->addPath($path);
        $this->getPaths()->shouldReturn(array($path));
    }

    function it_receives_and_returns_excludes(Location $exclude)
    {
        $this->addExclude($exclude);
        $this->getExcludes()->shouldReturn(array($exclude));
    }

}
