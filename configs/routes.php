<?php
#СБОР ОТЗЫВОВ В ПРИЛОЖЕНИИ
use Application\Main\CMIS\Middlewares\CredentialsValidatorMiddleware;

$this->get('app/cmis/retrieve', 'Application\Main\CMIS\Controllers\PasswordRetrievalController::retrieve')
->lazyMiddlewares([
    CredentialsValidatorMiddleware::class,

]);
$this->get('app/cmis/keygen/generate', 'Application\Main\CMIS\Controllers\KeygenGeneratorController::generate');
$this->get('app/accounts/create/from-excel', 'Application\Main\CMIS\Controllers\AccountManagerController::createFromExcelTable');
$this->post('app/accounts/create/from-app', 'Application\Main\CMIS\Controllers\AccountManagerController::createFromApp')
    ->lazyMiddlewares([
        \Application\Main\CMIS\Middlewares\AccountValidatorMiddleware::class,

    ]);
$this->get('app/accounts/passwords/generate/for-all', 'Application\Main\CMIS\Controllers\PasswordGeneratorController::generateForAll');
$this->delete('app/accounts/delete', 'Application\Main\CMIS\Controllers\AccountManagerController::delete');
$this->get('app/accounts/read-all', 'Application\Main\CMIS\Controllers\AccountManagerController::readAll');
#EXCEL GENERATOR
$this->get('app/excel-generator/generate/all-accounts-file', 'Application\Main\CMIS\Controllers\ExcelGeneratorController::generateAllAccountsFile');