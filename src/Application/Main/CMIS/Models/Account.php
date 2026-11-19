<?php

namespace Application\Main\CMIS\Models;

use Engine\Exceptions\UnknownPropertyException;

class Account implements \JsonSerializable
{
    private string|null $cmisAccountId;
    private string|null $cmisAccountName;
    private string|null $cmisAccountCurrentPassword;
    private string|null $cmisAccountNewPassword;
    private string|null $cmisAccountOwnerRealName;

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

    public function init(){
        $json = file_get_contents('php://input');
        $credentials = json_decode($json);
        $this->cmisAccountId = $credentials->cmisAccountId ?? null;
        $this->cmisAccountName = $credentials->cmisAccountName ?? null;
        $this->cmisAccountCurrentPassword = $credentials->cmisAccountCurrentPassword ?? null;
        $this->cmisAccountNewPassword = $credentials->cmisAccountNewPassword ?? null;
        $this->cmisAccountOwnerRealName = $credentials->cmisAccountOwnerRealName ?? null;
    }

}