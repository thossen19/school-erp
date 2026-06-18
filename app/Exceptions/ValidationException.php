<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

class ValidationException extends Exception
{
    protected MessageBag|array $errors;

    public function __construct(string $message = 'Validation failed', int $code = 422, ?MessageBag $errors = null)
    {
        parent::__construct($message, $code);
        $this->errors = $errors ?? new MessageBag();
    }

    public function getErrors(): MessageBag|array
    {
        return $this->errors;
    }

    public function render(Request $request): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
        ];

        if ($this->errors instanceof MessageBag && $this->errors->isNotEmpty()) {
            $response['errors'] = $this->errors->toArray();
        } elseif (is_array($this->errors) && !empty($this->errors)) {
            $response['errors'] = $this->errors;
        }

        return response()->json($response, $this->getCode());
    }
}
