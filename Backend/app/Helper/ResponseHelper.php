<?php

namespace App\Helper;

use Illuminate\Http\JsonResponse;

class ResponseHelper
{
    /**
     * Generate a standardized JSON response.
     *
     * @param mixed $data The data to be included in the response.
     * @param string|null $message An optional message to include in the response.
     * @param int $statusCode The HTTP status code for the response. Default is 200.
     * @return JsonResponse
     */
    public static function jsonResponse($success, $message, $data, $statusCode): JsonResponse
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }
}   