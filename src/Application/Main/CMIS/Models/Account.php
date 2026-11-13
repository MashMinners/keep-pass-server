<?php

namespace Application\Main\CMIS\Models;

use Engine\Exceptions\UnknownPropertyException;

class Account
{
    private $cmisAccountId;
    private $cmisAccountName;
    private $cmisAccountCurrentPassword;
    private $cmisAccountNewPassword;

    public function __get($name){
        if (property_exists($this, $property = $name)){
            return $this->$property;
        }
        throw new UnknownPropertyException("Свойство ".$property." не найдено в классе ".get_class($this));
    }

    public function __set($name, $value){
        if (property_exists($this, $property = ($name))){
            $this->$property = $value;
        }else{
            throw new UnknownPropertyException("Свойство ".$property." не найдено в классе ".get_class($this));
        }
    }

}