<?php namespace Dimsav\Backup\Element\Drivers;

use Dimsav\Backup\Element\Element;
use Exception;

class ExtractionFailureException extends Exception {

    /**
     * @var \Dimsav\Backup\Element\Element
     */
    protected $element;

    public function __construct(Element $element, $message, $code = 0, Exception $previous = null) {

        $this->element = $element;
        parent::__construct($message, $code, $previous);
    }

    public function getElement(){
        return $this->element;
    }

}