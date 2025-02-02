<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class CustomException extends Exception
{
    protected $response;

    public function __construct(Response $response, ?Throwable $previous = null)
    {
        parent::__construct($previous?->getMessage() ?? '', $previous?->getCode() ?? 0, $previous);

        $this->response = $response;
    }

    public function getResponse()
    {
        return $this->response;
    }
}
