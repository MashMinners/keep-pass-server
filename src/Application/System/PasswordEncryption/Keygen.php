<?php

namespace Application\System\PasswordEncryption;

class Keygen
{
    public static function generate(){
        $key = sodium_crypto_secretbox_keygen();
        $keyForStorage = sodium_bin2hex($key);
        $keyPath = 'configs/key';
        file_put_contents($keyPath, $keyForStorage);
        return $key;
    }

    public static function get(string $storage) : string {
        $key = file_get_contents($storage);
        return sodium_hex2bin($key);
    }

}