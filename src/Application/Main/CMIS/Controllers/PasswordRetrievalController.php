<?php

namespace Application\Main\CMIS\Controllers;

use Application\Main\CMIS\Models\PasswordRetrieval;
use Application\System\PasswordEncryption\Keygen;
use Application\System\PasswordEncryption\Sodium;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PasswordRetrievalController
{
    public function __construct(private PasswordRetrieval $retrieval)
    {

    }

    public function retrieve(ServerRequestInterface $request) : ResponseInterface {
        //$key = Keygen::generate();
        //$encrypted = Sodium::encryptPassword('12345', $key);
        $result = $this->retrieval->retrieve(['accountName' => 'kmf1', 'password' => '12345']);
        $a = 1;
    }

}