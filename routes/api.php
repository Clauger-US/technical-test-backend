<?php

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TicketController;


Route::get('/user', function (Request $request) {
    return $request->user();
});



Route::apiResource('tickets', TicketController::class);

// Enums
Route::get('/statuses', function () {
    return ApiResponse::success(
        TicketStatus::labels(),
        'Statuses fetched successfully'
    );
});

Route::get('/priorities', function () {
    return ApiResponse::success(
        TicketPriority::labels(),
        'Priorities fetched successfully'
    );
});
