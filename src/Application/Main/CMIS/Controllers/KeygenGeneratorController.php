<?php

namespace Application\Main\CMIS\Controllers;

use Application\System\PasswordEncryption\Keygen;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class KeygenGeneratorController
{
    public function generate(ServerRequestInterface $request) : ResponseInterface {
        Keygen::generate();
        return new JsonResponse('Ключ сгенерирован');
    }
}