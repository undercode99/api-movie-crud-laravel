<?php

namespace App\Services;

use App\Exceptions\ExceptionService;
use Illuminate\Support\Facades\Validator;

class BaseService
{
    protected function success($message = 'Success'): object
    {
        return (object) [
            'success' => true,
            'message' => $message,
            'code' => 200,
        ];
    }

    public function validateOrFail(array $data, array $rules, array $messages = [])
    {
        $validator = $this->validate($data, $rules, $messages);
        if ($validator->fails()) {
            throw ExceptionService::badRequest(
                'The given data was invalid.',
                $validator->errors(),
                422
            );
        }
    }

    public function validate(array $data, array $rules, array $messages = [])
    {
        return Validator::make($data, $rules, $messages);
    }
}
