<?php

namespace Application\Main\CMIS\Models;

use Engine\Exceptions\UnknownPropertyException;

class Account implements \JsonSerializable
{
    private $cmisAccountId;
    private $cmisAccountName;
    private $cmisAccountCurrentPassword;
    private $cmisAccountNewPassword;
    private $cmisAccountOwnerRealName;

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

    public function jsonSerialize(): array
    {
        return [
            'cmisAccountId' => $this->cmisAccountId,
            'cmisAccountName' => $this->cmisAccountName,
            'cmisAccountCurrentPassword' => $this->cmisAccountCurrentPassword,
            'cmisAccountNewPassword' => $this->cmisAccountNewPassword,
            'cmisAccountOwnerRealName' => $this->cmisAccountOwnerRealName,
        ];
    }

}