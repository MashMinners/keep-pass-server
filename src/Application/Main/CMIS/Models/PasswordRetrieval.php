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

    private function identify(Credentials $credentials){
        //Идентифициоруем пользователя
        $query = ("SELECT * FROM cmis_accounts WHERE cmis_account_name = :accountName");
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            'accountName' => $credentials->accountName
        ]);
        if ($stmt->rowCount() > 0) {
            return AccountFactory::create($stmt->fetch());
        }
        return false;
    }

    public function retrieve(Credentials $credentials){
        if ($account = $this->identify($credentials)){
            $key = Keygen::get('configs/key');
            $decryptedPassword = Sodium::decryptPassword($account->cmisAccountCurrentPassword, $key);
            if ($credentials->accountPassword === $decryptedPassword){
                return $account->cmisAccountNewPassword;
            }
        }
    }

}