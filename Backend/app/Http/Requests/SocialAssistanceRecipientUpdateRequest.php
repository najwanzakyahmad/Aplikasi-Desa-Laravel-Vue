<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SocialAssistanceRecipientUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            // 'social_assistance_id',
            // 'head_of_family_id',
            // 'bank',
            // 'amount',
            // 'reason',
            // 'account_number',
            // 'proof',
            // 'status'

            'social_assistance_id' => 'nullable|string|exists:social_assistances,id',
            'head_of_family_id' => 'nullable|string|exists:head_of_families,id',
            'bank' => 'required|string|in:BRI,BNI,BCA,Mandiri',
            'amount' => 'required|decimal:2|min:0',
            'reason' => 'required|string|max:2000',
            'account_number' => 'required|integer',
            'proof' => 'required|string',
            'status' => 'required|string|in:pending,approved,rejected',
        ];
    }

    public function attributes()
    {
        return [
            'social_assistance_id'  => 'Social Assistance ID',
            'head_of_family_id'     => 'ID Kepala Keluarga',
            'bank'                  => 'Bank',
            'amount'                => 'Jumlah',
            'reason'                => 'Alasan',
            'account_number'        => 'Nomor Rekening',
            'proof'                 => 'Bukti',
            'status'                => 'Status',
        ];
    }
}
