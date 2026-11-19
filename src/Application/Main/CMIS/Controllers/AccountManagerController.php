<?php

namespace Application\Main\CMIS\Controllers;

use Application\Main\CMIS\Models\AccountManager;
use Application\Main\CMIS\Models\ExcelParser;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AccountManagerController
{
    public function __construct(private AccountManager $manager){

    }

    public function createFromExcelTable(ServerRequestInterface $request) : ResponseInterface {
        /*
         * 1. Получить список из Excel
         * 2. Сформировать массив
         * 3. Передать в метот вставки
         */
        $accountsFromExcel = (new ExcelParser())->parseExcel(['storage/accounts.xlsx'], 'A2');
        $namedKeys = [];
        $i = 0;
        foreach ($accountsFromExcel AS $account){
            $namedKeys[$i]['cmisAccountName'] = $account['A'];
            $namedKeys[$i]['cmisAccountPassword'] = $account['B'];
            $namedKeys[$i]['cmisAccountOwnerRealName'] = $account['C'];
            $i++;
        }
        $result = $this->manager->create($namedKeys);
        return new JsonResponse('Количество вставленных записей '.count($accountsFromExcel));
    }

    public function createFromApp(ServerRequestInterface $request) : ResponseInterface {
        $credentials = $request->getAttribute('Account');
        //Таким образом я получу [0 => ['accountName' = 'Name', 'accountPassword' = 'password']]
        $this->manager->create([$credentials->jsonSerialize()]);
        return new JsonResponse("Аккаунт $credentials->cmisAccountName создан");
    }

    public function readAll(ServerRequestInterface $request) : ResponseInterface {
        $result = $this->manager->readAll();
        return new JsonResponse($result);
    }

    public function update(ServerRequestInterface $request) : ResponseInterface {
        $account = $request->getAttribute('Account');
        $this->manager->update($account);
        return new JsonResponse("Аккаунт $account->cmisAccountName обвновлен. ID $account->cmisAccountId");
    }

    public function delete(ServerRequestInterface $request) : ResponseInterface {
        $accounts = file_get_contents('php://input');
        $result = $this->manager->delete($accounts);
        $response = new JsonResponse($result);
        return $response;
    }

}