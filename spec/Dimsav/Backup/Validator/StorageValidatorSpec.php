<?php

namespace spec\Dimsav\Backup\Validator;

use Dimsav\Backup\Storage\Exceptions\TokenNotSetException;
use Dimsav\Backup\Storage\Storage;
use Dimsav\Backup\Storage\StorageFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class StorageValidatorSpec extends ObjectBehavior
{

    function it_returns_a_string_if_storages_are_not_valid(StorageFactory $storageFactory, Storage $storage_1, Storage $storage_2)
    {
        $exception1 = new TokenNotSetException('Omg, what have you done??');
        $exception2 = new TokenNotSetException('Forget about it. Go play some left 4 dead 2.');
        $storageFactory->makeAll()->willReturn(array($storage_1, $storage_2));
        $storage_1->validate()->shouldBeCalled()->willThrow($exception1);
        $storage_2->validate()->shouldBeCalled()->willThrow($exception2);

        $this->beConstructedWith($storageFactory);
        $this->validate();
        $this->getValidationErrorsString()->shouldBe("\n\n\nError 1: Omg, what have you done??\n\n\n\nError 2: Forget about it. Go play some left 4 dead 2.");
    }

    function it_returns_false_if_storages_are_valid(StorageFactory $storageFactory, Storage $storage_1, Storage $storage_2)
    {
        $storageFactory->makeAll()->willReturn(array($storage_1, $storage_2));

        $this->beConstructedWith($storageFactory);
        $this->validate();
        $this->getValidationErrorsString()->shouldReturn(false);
    }

}
