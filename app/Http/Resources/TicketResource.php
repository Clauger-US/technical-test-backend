<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'subject' => $this->subject,
            'priority' => $this->priority->label(),
            'status' => $this->status->label(),
            'created_at' => $this->created_at->format('m-Y-d H:i:s'),
            'updated_at' => $this->updated_at->format('m-Y-d H:i:s'),
        ];
    }
}
