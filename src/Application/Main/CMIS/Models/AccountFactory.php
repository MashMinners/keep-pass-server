<?php

namespace Application\Main\CMIS\Models;

use Engine\Utilities\StringFormatter;

class AccountFactory
{
    private static function formatField(string $field){
        $formattedField = StringFormatter::camelize($field);
        return $formattedField;
    }

    public static function create(array $accountData) : Account
    {
        $account = new Account();
        if ($accountData) {
            foreach ($accountData as $name => $value){
                $field = self::formatField($name);
                $account->$field = $value;
            }
        }
        return $account;
    }

}