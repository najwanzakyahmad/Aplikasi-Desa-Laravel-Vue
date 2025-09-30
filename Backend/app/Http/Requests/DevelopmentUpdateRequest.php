<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DevelopmentUpdateRequest extends FormRequest
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
            'thumbnail'         => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'name'              => 'required|string|max:255',
            'description'       => 'required|string|max:1000',
            'person_in_charge'  => 'required|string|max:255',
            'start_date'        => 'required|date|date_format:Y-m-d',
            'end_date'          => 'required|date|date_format:Y-m-d|after_or_equal:start_date',
            'amount'            => 'required|decimal:2|min:0',
            'status'            => 'required|string|in:onGoing,completed',
        ];
    }

    public function attributes()
    {
        return [
            'thumbnail'         => 'Thumbnail',
            'name'              => 'Nama',
            'description'       => 'Deskripsi',
            'person_in_charge'  => 'Penanggungjawab',
            'start_date'        => 'Tanggal Mulai',
            'end_date'          => 'Tanggal Selesai',
            'amount'            => 'Jumlah',
            'status'            => 'Status',
        ];
    }
}
