<?php

namespace Application\Main\CMIS\Controllers;

use Application\Main\CMIS\Models\PasswordGenerator;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PasswordGeneratorController
{
    public function __construct(private PasswordGenerator $generator){

    }

    public function generateForAll(ServerRequestInterface $request) : ResponseInterface {
        $newCredentials = $this->generator->generateForAll();
        return new JsonResponse($newCredentials);
    }

}