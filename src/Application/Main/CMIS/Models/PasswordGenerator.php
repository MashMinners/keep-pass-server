<?php

namespace Application\Main\CMIS\Models;

use Application\System\PasswordEncryption\Keygen;
use Application\System\PasswordEncryption\Sodium;
use Engine\Database\IConnector;

class PasswordGenerator
{
    public function __construct(IConnector $connector){
        $this->pdo = $connector::connect();
    }
    /**
     * Цель простая, получить все даныне об аккаунтах данной системы
     * Далее каждое поле
     * @return array|false
     */
    private function getAllAccounts() : array{
        $query = ("SELECT * FROM cmis_accounts");
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    /**
     * Генерирует новый пароль на основе имени пользователя типа:
     * Elen@-22
     * Dari@-46
     * @param string $realName
     * @return string
     */
    private function generateEncryptedPassword(string $oldPasswordHash, string $realName) : string{
        //Получаю секретный ключ
        $key = Keygen::get('configs/key');
        // Транслитерация русских букв в латиницу
        $translit = [
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd',
            'е' => 'e', 'ё' => 'e', 'ж' => 'zh', 'з' => 'z', 'и' => 'i',
            'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
            'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'ts', 'ч' => 'ch',
            'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'y', 'ь' => '',
            'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
        ];

        // Приводим к нижнему регистру и транслитерируем
        $latin = strtr(mb_strtolower(trim($realName)), $translit);

        // Оставляем только буквы и цифры
        $latin = preg_replace('/[^a-z0-9]/', '', $latin);

        if ($latin === '') {
            $latin = 'user';
        }
        // Делаем первую букву заглавной
        $base = mb_convert_case($latin, MB_CASE_TITLE, 'UTF-8');
        // Заменяем ПЕРВУЮ букву 'a'/'A' на '@'
        $base = preg_replace('/[aA]/', '@', $base, 1);
        // Случайное число от 10 до 1000
        $number = random_int(10, 1000);
        $symbols = ['@', '#', '$', '!', '%', '&', '*'];
        $symbol  = $symbols[array_rand($symbols)];
        $oldPassword  = Sodium::decryptPassword($oldPasswordHash, $key);
        $newPassword = $base . '-' . $number.$symbol;
        if ($newPassword === $oldPassword){
            $newPassword = $newPassword.'!';
        }
        return Sodium::encryptPassword($newPassword, $key);
    }
    private function getAccountsWithNewPasswords(array $accounts) : array {
        $newPasswords = [];
        $i = 0;
        foreach ($accounts AS $row){
            $account = AccountFactory::create($row);
            $account->cmisAccountNewPassword = $this->generateEncryptedPassword($account->cmisAccountCurrentPassword, $account->cmisAccountOwnerRealName);
            $newPasswords[$i] = $account;
            $i++;
        }
        return $newPasswords;
    }
    private function updateNewPasswords(array $accounts) : array{
        $key = Keygen::get('configs/key');
        $cases   = [];
        $inList  = [];
        $params  = [];
        $credentials = [];
        $i = 0;
        foreach ($accounts as $account){
            $cases[]                  = "WHEN :id_$i THEN :pwd_$i";
            $inList[]                 = ":in_id_$i";   // отдельное имя — чтобы не дублировать параметр
            $params["id_$i"]          = $account->cmisAccountId;
            $params["pwd_$i"]         = $account->cmisAccountNewPassword;
            $params["in_id_$i"]       = $account->cmisAccountId;
            $credentials[$i]['cmisAccountName'] = $account->cmisAccountName;
            $credentials[$i]['cmisAccountPassword'] = Sodium::decryptPassword($account->cmisAccountNewPassword, $key);
            $i++;
        }
        $query = "UPDATE cmis_accounts SET cmis_account_new_password = CASE cmis_account_id
        " . implode(' ', $cases) . "
        END
        WHERE cmis_account_id IN (" . implode(',', $inList) . ")";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $credentials;
    }
    public function generateForAll(){
        //Получаю все данные о текущих параметрах
        $accountsData = $this->getAllAccounts();
        //В соответсвия с каждым ID генерируется новый пароль, там же он и шифруется
        $accounts = $this->getAccountsWithNewPasswords($accountsData);
        //Обновляю пароль для аккаунтов
        $credentials = $this->updateNewPasswords($accounts);
        return  $credentials;
    }

}