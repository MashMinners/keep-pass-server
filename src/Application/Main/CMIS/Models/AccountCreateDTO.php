<?php

namespace Application\Main\CMIS\Models;

use Engine\Exceptions\UnknownPropertyException;

class AccountCreateDTO implements \JsonSerializable
{
    private $cmisAccountName;
    private $cmisAccountPassword;
    private $cmisAccountOwnerRealName;

    public function __construct() {
        $json = file_get_contents('php://input');
        $credentials = json_decode($json);
        $this->cmisAccountName = $credentials->cmisAccountName;
        $this->cmisAccountPassword = $credentials->cmisAccountPassword;
        $this->cmisAccountOwnerRealName = $credentials->cmisAccountOwnerRealName;
    }

    public function __get($name)
    {
        if (property_exists($this, $property = $name)){
            return $this->$property;
        }
        throw new UnknownPropertyException("Свойство ".$property." не найдено в классе ".get_class($this));
    }

    public function jsonSerialize(): array
    {
        return [
            'cmisAccountName' => $this->cmisAccountName,
            'cmisAccountPassword' => $this->cmisAccountPassword,
            'cmisAccountOwnerRealName' => $this->cmisAccountOwnerRealName
        ];
    }

}