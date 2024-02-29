<?php

namespace App\Exceptions;

use Exception;

class ExceptionService extends Exception
{
    public const NOT_FOUND = 404;

    public $errors = [];

    public function __construct($message = '', $code = 0, $errors = [], ?Exception $previous = null)
    {
        $this->errors = $errors;
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__.": [{$this->code}]: {$this->message}\n";
    }

    public static function notFound($message = 'Data not found')
    {
        return new ExceptionService($message, self::NOT_FOUND);
    }

    public static function badRequest($message = 'Bad request', $errors = [], $code = 400)
    {
        return new ExceptionService($message, $code, $errors);
    }

    public static function internalServerError($message = 'Internal server error')
    {
        return new ExceptionService($message, 500);
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
