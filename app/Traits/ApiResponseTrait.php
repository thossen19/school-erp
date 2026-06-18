<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    protected int $statusCode = 200;

    protected array $additionalHeaders = [];

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): static
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function respondWithHeaders(array $headers): static
    {
        $this->additionalHeaders = $headers;

        return $this;
    }

    public function successResponse($data = null, string $message = 'Success', int $code = 200): JsonResponse
    {
        return $this->respond([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public function createdResponse($data = null, string $message = 'Created successfully'): JsonResponse
    {
        return $this->successResponse($data, $message, 201);
    }

    public function updatedResponse($data = null, string $message = 'Updated successfully'): JsonResponse
    {
        return $this->successResponse($data, $message, 200);
    }

    public function deletedResponse(string $message = 'Deleted successfully'): JsonResponse
    {
        return $this->successResponse(null, $message, 200);
    }

    public function errorResponse(string $message = 'Error occurred', int $code = 400, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return $this->respond($response, $code);
    }

    public function validationErrorResponse($errors, string $message = 'Validation failed'): JsonResponse
    {
        return $this->errorResponse($message, 422, $errors);
    }

    public function notFoundResponse(string $message = 'Resource not found'): JsonResponse
    {
        return $this->errorResponse($message, 404);
    }

    public function unauthorizedResponse(string $message = 'Unauthorized'): JsonResponse
    {
        return $this->errorResponse($message, 401);
    }

    public function forbiddenResponse(string $message = 'Forbidden'): JsonResponse
    {
        return $this->errorResponse($message, 403);
    }

    public function internalServerErrorResponse(string $message = 'Internal server error'): JsonResponse
    {
        return $this->errorResponse($message, 500);
    }

    public function paginatedResponse($paginator, string $message = 'Success'): JsonResponse
    {
        $data = $paginator->items();

        return $this->respond([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
        ], 200);
    }

    protected function respond(array $data, int $code = null): JsonResponse
    {
        $code = $code ?? $this->statusCode;

        $response = response()->json($data, $code);

        if (!empty($this->additionalHeaders)) {
            foreach ($this->additionalHeaders as $key => $value) {
                $response->header($key, $value);
            }
            $this->additionalHeaders = [];
        }

        return $response;
    }
}
