<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class ServiceException extends Exception
{
    protected mixed $data;

    public function __construct(string $message = '', int $code = 500, ?Throwable $previous = null, mixed $data = null)
    {
        parent::__construct($message, $code, $previous);
        $this->data = $data;
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function render(Request $request): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
        ];

        if ($this->data !== null) {
            $response['data'] = $this->data;
        }

        if (config('app.debug') && $this->getPrevious()) {
            $response['exception'] = get_class($this->getPrevious());
            $response['trace'] = $this->getPrevious()->getTraceAsString();
        }

        return response()->json($response, $this->getCode());
    }
}
