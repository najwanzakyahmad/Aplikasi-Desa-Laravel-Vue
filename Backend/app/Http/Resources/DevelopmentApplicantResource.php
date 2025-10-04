<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DevelopmentApplicantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'development_id'    => $this->development_id,
            'user_id'           => $this->user_id,
            'status'            => $this->status,
            'development'       => new DevelopmentResource($this->whenLoaded('development')),
            'user'              => new UserResource($this->whenLoaded('user'))
        ];
    }
}
