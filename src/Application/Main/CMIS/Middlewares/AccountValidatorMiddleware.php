<?php

namespace Application\Main\CMIS\Middlewares;

use Application\Main\CMIS\Models\Account;
use Application\Main\CMIS\Models\AccountValidationErrors;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AccountValidatorMiddleware implements MiddlewareInterface
{
    public function __construct(private Account $account, private AccountValidationErrors $errors){
        $this->account->init();
    }

    private function validateAccountName() : void
    {
        if (!empty($accountName = $this->account->cmisAccountName)){
            if (!preg_match("/^[a-zA-Z0-9_]+$/", $accountName)){
                $this->errors->incorrectCharacters('cmisAccountName');
            }
        }else{
            $this->errors->emptyField('cmisAccountName');
        }
    }

    private function validateCurrentPassword() : void
    {
        if (!empty($accountPassword = $this->account->cmisAccountCurrentPassword)){
            if (!preg_match("/^[a-zA-Z0-9!@#$%^&*()-_=+]+$/", $accountPassword)){
                $this->errors->incorrectCharacters('cmisAccountCurrentPassword');
            }
        }else{
            $this->errors->emptyField('cmisAccountCurrentPassword');
        }
    }

    private function validateOwnerName() : void {
        if (!empty($accountPassword = $this->account->cmisAccountOwnerRealName)){
            if (!preg_match("/^[А-Яа-яЁё]+$/u", $accountPassword)){
                $this->errors->incorrectCharacters('cmisAccountOwnerRealName');
            }
        }else{
            $this->errors->emptyField('cmisAccountOwnerRealName');
        }
    }

    /**
     * Process an incoming server request.
     *
     * Processes an incoming server request in order to produce a response.
     * If unable to produce the response itself, it may delegate to the provided
     * request handler to do so.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->validateAccountName();
        $this->validateCurrentPassword();
        $this->validateOwnerName();
        if ($this->errors->hasErrors()) {
            return new JsonResponse($this->errors, 406);
        }
        $request = $request->withAttribute('Account', $this->account);
        return $response = $handler->handle($request);
    }

}