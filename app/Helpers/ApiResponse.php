<?php

namespace App\Helpers;

class ApiResponse
{
    public static function success($data = null, string $message = null, int $code = 200): array
    {
        return [
            'success' => true,
            'data' => $data,
            'message' => $message,
            'code' => $code
        ];
    }

    public static function error(string $message, int $code = 400, $data = null): array
    {
        return [
            'success' => false,
            'data' => $data,
            'message' => $message,
            'code' => $code
        ];
    }
}
