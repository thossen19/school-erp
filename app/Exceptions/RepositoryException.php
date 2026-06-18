<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class RepositoryException extends Exception
{
    protected ?array $context;

    public function __construct(string $message = '', int $code = 500, ?Throwable $previous = null, ?array $context = null)
    {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
    }

    public function getContext(): ?array
    {
        return $this->context;
    }

    public function render(Request $request): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
        ];

        if (config('app.debug') && $this->getPrevious()) {
            $response['exception'] = get_class($this->getPrevious());
            $response['trace'] = $this->getPrevious()->getTraceAsString();
        }

        return response()->json($response, $this->getCode());
    }
}
