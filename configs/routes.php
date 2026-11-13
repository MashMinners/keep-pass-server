<?php
#СБОР ОТЗЫВОВ В ПРИЛОЖЕНИИ
use Application\Main\CMIS\Middlewares\CredentialsValidatorMiddleware;

$this->get('app/cmis/retrieve', 'Application\Main\CMIS\Controllers\PasswordRetrievalController::retrieve')
->lazyMiddlewares([
    CredentialsValidatorMiddleware::class,

]);
$this->get('app/cmis/keygen/generate', 'Application\Main\CMIS\Controllers\KeygenGeneratorController::generate');