<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class NotifyException extends \Exception
{
    public function __construct(string $message = '', $code = Response::HTTP_BAD_REQUEST)
    {
        parent::__construct($message,$code);
    }
}