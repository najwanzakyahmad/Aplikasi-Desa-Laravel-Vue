<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventUpdateRequest extends FormRequest
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
            'thumbnail'     => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'name'          => 'required|string|max:255',
            'description'   => 'required|string|max:1000',
            'price'         => 'required|decimal:2|min:0',
            'date'          => 'required|date|date_format:Y-m-d|after_or_equal:today',
            'time'          => 'required|date_format:H:i',
            'is_active'     => 'sometimes|boolean',
        ];
    }

    public function attributes()
    {
        return [
            'thumbnail'     => 'Thumbnail',
            'name'          => 'Nama',
            'description'   => 'Deskripsi',
            'price'         => 'Harga',
            'date'          => 'Tanggal',
            'time'          => 'Waktu',
            'is_active'     => 'Is Active',
        ];
    }
}
