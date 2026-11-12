<?php

namespace Application\System\PasswordEncryption;

class Sodium
{

// Функция шифрования
    public static function encryptPassword(string $plaintext, string $key): string {
        // Генерация случайного nonce для каждого шифрования (24 байта)
        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $ciphertext = sodium_crypto_secretbox($plaintext, $nonce, $key);
        // Сохраняем nonce и зашифрованный текст вместе, в кодировке base64
        return base64_encode($nonce . $ciphertext);
    }

// Функция дешифрования
    public static function decryptPassword(string $stored, string $key): string|false {
        $decoded = base64_decode($stored, true);
        if ($decoded === false || strlen($decoded) < SODIUM_CRYPTO_SECRETBOX_NONCEBYTES) {
            return false;
        }
        $nonce = substr($decoded, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $ciphertext = substr($decoded, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        return sodium_crypto_secretbox_open($ciphertext, $nonce, $key);
    }

}