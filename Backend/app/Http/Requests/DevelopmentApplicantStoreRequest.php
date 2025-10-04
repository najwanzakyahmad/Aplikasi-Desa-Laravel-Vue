<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DevelopmentApplicantStoreRequest extends FormRequest
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
            'development_id'    => 'required|string|exists:developments,id',
            'user_id'           => 'required|string|exists:user,id',
            'status'            => 'required|in:pending,approved,rejected,',
        ];
    }

    public function attributes()
    {
        return [
            'development_id'    => 'Pengembangan',
            'user_id'           => 'Pengguna',
            'quantity'          => 'Status',
        ];
    }
}
