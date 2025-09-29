<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SocialAssistanceStoreRequest extends FormRequest
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
            'thumbnail'     => 'required|image|mimes:jpg,png,jpeg|max:2048',
            'name'          => 'required|string|max:255',
            'category'      => 'required|string|in:staple,cash,subsidized fuel,health',
            'amount'        => 'required|decimal:2|min:0',
            'provider'      => 'required|string|max:255',
            'description'   => 'required|string|max:1000',
            'is_available'  => 'sometimes|boolean',
        ];
    }

    public function attributes()
    {
        return [
            'thumbnail'     => 'Thumbnail',
            'name'          => 'Nama',
            'category'      => 'Kategori',
            'amount'        => 'Jumlah',
            'provider'      => 'Penyedia',
            'description'   => 'Deskripsi',
            'is_available'  => 'is_available'
        ];
    }
}
