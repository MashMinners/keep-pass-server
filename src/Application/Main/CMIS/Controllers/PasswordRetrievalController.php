<?php

namespace Application\Main\CMIS\Controllers;

use Application\Main\CMIS\Models\PasswordRetrieval;
use Application\System\PasswordEncryption\Keygen;
use Application\System\PasswordEncryption\Sodium;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PasswordRetrievalController
{
    public function __construct(private PasswordRetrieval $retrieval)
    {

    }

    public function retrieve(ServerRequestInterface $request) : ResponseInterface {
        $key = Keygen::get('configs/key');
        //$encrypted = Sodium::encryptPassword('22222', $key);
        $credentials = $request->getAttribute('Credentials');
        $result = $this->retrieval->retrieve($credentials);
        $newPassword = Sodium::decryptPassword($result, $key);
        return new JsonResponse('Новый пароль '.$newPassword);
    }

}