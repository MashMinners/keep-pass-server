<?php

namespace Application\Main\CMIS\Controllers;

use Application\Main\CMIS\Models\AccountManager;
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
        $accountsFromExcel = [];
        $result = $this->manager->create($accountsFromExcel);
        return new JsonResponse('Количество вставленных записей '.count($accountsFromExcel));
    }

    public function createFromApp(ServerRequestInterface $request) : ResponseInterface {
        $credentials = $request->getAttribute('Credentials');
        //Таким образом я получу [0 => ['accountName' = 'Name', 'accountPassword' = 'password']]
        $this->manager->create([$credentials->jsonSerialize()]);
        return new JsonResponse("Аккаунт $credentials->accountName создан");
    }

}