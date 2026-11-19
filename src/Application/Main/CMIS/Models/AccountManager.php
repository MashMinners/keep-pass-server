<?php

namespace Application\Main\CMIS\Models;

use Application\System\PasswordEncryption\Keygen;
use Application\System\PasswordEncryption\Sodium;
use Engine\Database\IConnector;
use Engine\Utilities\StringFormatter;
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
        foreach ($accounts AS $account){
            $query .= '(';
            $accountPassword = StringFormatter::wrapInQuotes(Sodium::encryptPassword($account['cmisAccountPassword'], $key));
            $accountId= StringFormatter::wrapInQuotes(Uuid::uuid4()->toString());
            $accountName = StringFormatter::wrapInQuotes($account['cmisAccountName']);
            $accountOwnerRealName = StringFormatter::wrapInQuotes($account['cmisAccountOwnerRealName']);
            //$query .= "'".$accountId."', '".$accountName."', '".$accountPassword."', '','".$accountOwnerRealName."'";
            $query .= $accountId.', '.$accountName.', '.$accountPassword.", '', ".$accountOwnerRealName;
            $query .= '),';
        }
        $query = preg_replace('/,\s*$/', '', $query);
        $stmt = $this->pdo->prepare($query);
        if ($stmt->execute()){
            return true;
        }
        return false;

    }

    public function read(string $id){

    }

    public function readAll(){
        $query = ("SELECT * FROM cmis_accounts");
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
        if (!empty($stmt)){
            $key = Keygen::get('configs/key');
            $i = 0;
            foreach ($result AS $account){
                $accounts[$i]['cmisAccountId'] = $account['cmis_account_id'];
                $accounts[$i]['cmisAccountName'] = $account['cmis_account_name'];
                $accounts[$i]['cmisAccountCurrentPassword'] = Sodium::decryptPassword($account['cmis_account_current_password'], $key);
                $accounts[$i]['cmisAccountNewPassword'] = Sodium::decryptPassword($account['cmis_account_new_password'], $key);
                $accounts[$i]['cmisAccountOwnerRealName'] = $account['cmis_account_owner_real_name'];
                $i++;
            }
            return $accounts;
        }
        return $result;

    }

    /*
     * Обновляет учетные записи на основе массива с их ID
     */
    public function update(array $accounts){

    }

    /*
     * Удаляет от 1-го до нескольких аккаунтов из БД на основе их (его) ID
     */
    public function delete(string $accounts){
        $accounts = json_decode($accounts);
        $query = ("DELETE FROM cmis_accounts WHERE cmis_account_id IN");
        $query .= "(";
        foreach ($accounts->accountsIDs as $accountId){
            $query .= StringFormatter::wrapInQuotes($accountId)." ,";
        }
        $query =  mb_substr($query, 0, -1);
        $query .= ")";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return true;
    }


}