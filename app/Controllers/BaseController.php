<?php

namespace App\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class BaseController
{
    /**
     * Return success response
     *
     * @param mixed $data
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function successResponse($data, string $message = 'Success', int $statusCode = 200): JsonResponse
    {
        if (is_null($data)) {
            return response()->json([
                'message' => $message,
                'status' => 'success',
            ], $statusCode);
        }

        // If not already wrapped in a specific structure, create the standard response format
        if (!isset($data['data']) && ($data === [] || !is_array($data) || !array_key_exists('current_page', $data))) {
            return response()->json([
                'message' => $message,
                'status' => 'success',
                'data' => $data
            ], $statusCode);
        }

        // If it's already in a paginated format, add message and status
        return response()->json(array_merge([
            'message' => $message,
            'status' => 'success',
        ], $data), $statusCode);
    }

    /**
     * Return error response
     *
     * @param string $message
     * @param int $statusCode
     * @param array $errors
     * @return JsonResponse
     */
    protected function errorResponse(string $message, int $statusCode = 400, array $errors = []): JsonResponse
    {
        $response = [
            'message' => $message,
            'status' => 'error'
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Return validation error response
     *
     * @param array $errors
     * @return JsonResponse
     */
    protected function validationErrorResponse(array $errors): JsonResponse
    {
        return $this->errorResponse('Validation failed', 422, $errors);
    }
}
