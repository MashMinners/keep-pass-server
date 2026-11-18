<?php

namespace Application\Main\CMIS\Middlewares;

use Application\Main\CMIS\Models\AccountCreateDTO;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CreateAccountDTOValidatorMiddleware implements MiddlewareInterface
{
    public function __construct (private AccountCreateDTO $DTO){

    }

    private function validateDTO() : bool {
        if(!preg_match("/^[0-9A_Za-z]+$/u", $this->DTO->cmisAccountName)){return false;}
        if(!preg_match("/^[0-9A-Za-z@#$!%&*]+$/u", $this->DTO->cmisAccountPassword)){return false;}
        if(!preg_match("/^[А-Яа-яЁё]+$/u", $this->DTO->cmisAccountOwnerRealName)){return false;}
        return true;
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
        if (!$this->validateDTO()){
            return new JsonResponse('Проверьте вводимые данные, имя пользователя, пароль или имя владельца', 406);
        }

        $request = $request->withAttribute('CreateAccountDTO', $this->DTO);
        return $response = $handler->handle($request);
    }

}