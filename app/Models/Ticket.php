<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\TicketStatus;
use App\Enums\TicketPriority;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'priority',
        'status',
    ];

    protected $casts = [
        'status' => TicketStatus::class,
        'priority' => TicketPriority::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}