<?php

namespace Application\Main\CMIS\Models;

use Engine\Exceptions\UnknownPropertyException;

class Credentials
{
    private string $accountName;
    private string $accountPassword;

    public function __construct() {
        $json = file_get_contents('php://input');
        $credentials = json_decode($json);
        $this->accountName = $credentials->accountName;
        $this->accountPassword = $credentials->accountPassword;
    }

    public function __get($name)
    {
        if (property_exists($this, $property = $name)){
            return $this->$property;
        }
        throw new UnknownPropertyException("Свойство ".$property." не найдено в классе ".get_class($this));
    }
}