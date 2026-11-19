<?php

namespace Application\Main\CMIS\Controllers;

use Application\Main\CMIS\Models\AccountManager;
use Application\Main\CMIS\Models\ExcelGenerator;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ExcelGeneratorController
{
    public function __construct(private AccountManager $manager, private ExcelGenerator $generator){

    }

    public function generateAllAccountsFile(ServerRequestInterface $request) : ResponseInterface {
        $result = $this->manager->readAll();
        $xlsHeader = ['ID аккаунта', 'Имя аккаунта', 'Текущий пароль', 'Новый пароль', 'Имя пользователя'];
        (new ExcelGenerator())->generate('Новые пароли', $xlsHeader, $result);
        return new JsonResponse($result);
    }

}