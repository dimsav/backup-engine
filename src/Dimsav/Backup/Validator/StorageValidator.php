<?php namespace Dimsav\Backup\Validator;

use Dimsav\Backup\Storage\Exceptions\InvalidStorageException;
use Dimsav\Backup\Storage\Exceptions\TokenNotSetException;
use Dimsav\Backup\Storage\StorageFactory;

class StorageValidator
{

    /**
     * @var \Dimsav\Backup\Storage\StorageFactory
     */
    private $storageFactory;

    private $errors = array();

    public function __construct(StorageFactory $storageFactory)
    {
        $this->storageFactory = $storageFactory;
    }

    public function validate()
    {
        foreach ($this->storageFactory->makeAll() as $storage)
        {
            try {
                $storage->validate();
            }
            catch (TokenNotSetException $e) {
                $this->errors[] = $e->getMessage();
            }
            catch (InvalidStorageException $e) {
                $this->errors[] = $e->getMessage();
            }
        }
    }


    /**
     * Returns false if no errors where found, else, a string with the error messages.
     *
     * @return false|string
     */
    public function getValidationErrorsString()
    {
        foreach ($this->errors as $key => $error)
        {
            $count = $key+1;
            $this->errors[$key] = "\n\n\nError $count: " . $error;
        }
        if ($this->errors)
        {
            return implode("\n", $this->errors);
        }
        return false;
    }
}
