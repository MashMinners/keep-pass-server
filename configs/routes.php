<?php
#СБОР ОТЗЫВОВ В ПРИЛОЖЕНИИ
use Application\Main\CMIS\Middlewares\CreateAccountDTOValidatorMiddleware;
use Application\Main\CMIS\Middlewares\CredentialsValidatorMiddleware;

$this->get('app/cmis/retrieve', 'Application\Main\CMIS\Controllers\PasswordRetrievalController::retrieve')
->lazyMiddlewares([
    CredentialsValidatorMiddleware::class,

]);
$this->get('app/cmis/keygen/generate', 'Application\Main\CMIS\Controllers\KeygenGeneratorController::generate');
$this->get('app/accounts/create/from-excel', 'Application\Main\CMIS\Controllers\AccountManagerController::createFromExcelTable');
$this->post('app/accounts/create/from-app', 'Application\Main\CMIS\Controllers\AccountManagerController::createFromApp')
    ->lazyMiddlewares([
        CreateAccountDTOValidatorMiddleware::class,

    ]);
$this->get('app/accounts/passwords/generate/for-all', 'Application\Main\CMIS\Controllers\PasswordGeneratorController::generateForAll');