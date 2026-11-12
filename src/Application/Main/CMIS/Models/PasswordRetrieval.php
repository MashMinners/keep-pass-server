<?php

namespace Application\Main\CMIS\Models;

use Application\System\PasswordEncryption\Keygen;
use Application\System\PasswordEncryption\Sodium;
use Engine\Database\IConnector;

class PasswordRetrieval
{
    private \PDO $pdo;
    public function __construct(IConnector $connector){
        $this->pdo = $connector::connect();
    }

    private function identify(array $credentials){
        //Идентифициоруем пользователя
        $query = ("SELECT * FROM cmis_accounts WHERE cmis_account_name = :accountName");
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            'accountName' => $credentials['accountName']
        ]);
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch();
        }
        return false;
    }

    public function retrieve(array $credentials){
        if ($account = $this->identify($credentials)){
            $key = Keygen::get('configs/key');
            $decryptedPassword = Sodium::decryptPassword($account['cmis_account_current_password'], $key);
            if ($credentials['password'] === $decryptedPassword){
                return $account['cmis_account_new_password'];
            }
        }
    }

}