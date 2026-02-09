<?php

namespace App\Helpers;

class ApiResponse
{
    public static function success($data = null, string $message = null, int $status = 200): array
    {
        return [
            'success' => true,
            'data' => $data,
            'message' => $message,
        ];
    }

    public static function error(string $message, int $status = 400, $data = null): array
    {
        return [
            'success' => false,
            'data' => $data,
            'message' => $message,
        ];
    }
}
