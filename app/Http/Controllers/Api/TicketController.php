<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Http\Resources\TicketResource;
use App\Helpers\ApiResponse;
use App\Enums\TicketStatus;
use App\Enums\TicketPriority;
use Illuminate\Validation\Rule;

class TicketController extends Controller
{
    // List
    public function index(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'status' => ['nullable', Rule::in(array_column(TicketStatus::cases(), 'value'))],
            'priority' => ['nullable', Rule::in(array_column(TicketPriority::cases(), 'value'))],
            'search' => ['nullable', 'string', 'max:255'],
            'sort_by' => ['nullable', Rule::in(['created_at', 'priority', 'status'])],
            'sort_order' => ['nullable', Rule::in(['asc', 'desc'])],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $query = Ticket::query();

        // Filters
        if (!empty($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        if (!empty($validated['priority'])) {
            $query->where('priority', $validated['priority']);
        }

        if (!empty($validated['search'])) {
            $query->where('subject', 'like', '%' . $validated['search'] . '%');
        }

        // Sort
        $sortBy = $validated['sort_by'] ?? 'created_at';
        $sortOrder = $validated['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        // pagination
        $perPage = $validated['per_page'] ?? 10;
        $tickets = $query->paginate($perPage);

        return ApiResponse::success([
            'success' => true,
            'data' => TicketResource::collection($tickets),
            'meta' => [
                'current_page' => $tickets->currentPage(),
                'per_page' => $tickets->perPage(),
                'total' => $tickets->total(),
                'last_page' => $tickets->lastPage(),
            ],
            'message' => null
        ]);
    }


    // Create
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'priority' => ['required', Rule::in(array_column(TicketPriority::cases(), 'value'))],
            'status' => ['required', Rule::in(array_column(TicketStatus::cases(), 'value'))],
        ]);

        $ticket = Ticket::create($validated);
        return ApiResponse::success(ApiResponse::success(new TicketResource($ticket), 'Ticket created'), 201);
    }

    // Show
    public function show(Ticket $ticket)
    {
        return ApiResponse::success(ApiResponse::success(new TicketResource($ticket)));
    }

    // Edit
    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'subject' => 'sometimes|required|string|max:255',
            'priority' => ['sometimes', Rule::in(array_column(TicketPriority::cases(), 'value'))],
            'status' => ['sometimes', Rule::in(array_column(TicketStatus::cases(), 'value'))],
        ]);

        $ticket->update($validated);
        return ApiResponse::success(ApiResponse::success(new TicketResource($ticket), 'Ticket updated'));
    }

    // Delete
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return ApiResponse::success(ApiResponse::success(null, 'Ticket deleted'));
    }
}
