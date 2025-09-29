<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SocialAssistanceRecipientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            // 'social_assistance_id' => new SocialAssistanceResource($this->whenLoaded('socialAssistance')),
            'head_of_family_id' => new HeadOfFamilyResource($this->whenLoaded('headOfFamily')),
            'bank' => $this->bank,
            'amount' => $this->amount,
            'reason' => $this->reason,
            'account_number' => $this->account_number,
            'proof' => $this->proof,
            'status'  => $this->status,
            'social_assistance_id' => $this->social_assistance_id,
            // 'head_of_family_id' => $this->head_of_family_id,
        ];
    }
}
