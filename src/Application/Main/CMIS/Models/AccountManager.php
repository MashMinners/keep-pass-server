<?php

namespace Application\Main\CMIS\Models;

use Application\System\PasswordEncryption\Keygen;
use Application\System\PasswordEncryption\Sodium;
use Engine\Database\IConnector;
use Ramsey\Uuid\Uuid;

class AccountManager
{
    private \PDO $pdo;
    public function __construct(IConnector $connector){
        $this->pdo = $connector::connect();
    }

    /**
     * На вход будет даватсья от 1-го до нескольких записей с аккаунтами пользвоателей
     * @param array $accounts
     * @return void
     */
    public function create(array $accounts) : bool{
        $key = Keygen::get('configs/key');
        $query = ("INSERT INTO cmis_accounts (cmis_account_id, cmis_account_name, cmis_account_current_password, cmis_account_new_password, cmis_account_owner_real_name)
                   VALUES ");
        $query .= '(';
        foreach ($accounts AS $account){
            $accountPassword = Sodium::encryptPassword($account['cmisAccountPassword'], $key);
            $accountId= Uuid::uuid4()->toString();
            $accountName = $account['cmisAccountName'];
            $accountOwnerRealName = $account['cmisAccountOwnerRealName'];
            $query .= "'".$accountId."', '".$accountName."', '".$accountPassword."', '','".$accountOwnerRealName."'";
        }
        $query .= ')';
        $stmt = $this->pdo->prepare($query);
        if ($stmt->execute()){
            return true;
        }
        return false;

    }

    public function read(string $id){

    }

    /*
     * Обновляет учетные записи на основе массива с их ID
     */
    public function update(array $accounts){

    }

    /*
     * Удаляет от 1-го до нескольких аккаунтов из БД на основе их (его) ID
     */
    public function delete(array $accounts){

    }


}