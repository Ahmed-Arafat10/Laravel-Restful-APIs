<?php

namespace App\Exceptions;


use App\Traits\ApiResponser;
use Illuminate\Validation\ValidationException;

class customFormValidationException extends \Illuminate\Validation\ValidationException
{
    use ApiResponser;

    public static function customValidationException(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();
        return static::errorResponseS($errors, 422);
    }
}
