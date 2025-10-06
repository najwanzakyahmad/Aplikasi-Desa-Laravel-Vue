<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileStoreRequest extends FormRequest
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
            'thumbnail'         => 'required|image|mimes:jpg,png,jpeg|max:2048',
            'name'              => 'required|string',
            'about'             => 'required|string',
            'headman'           => 'required|string',
            'people'            => 'required|integer',
            'agricultural_area' => 'required',
            'total_area'        => 'required',
            'images'            => 'nullable|array',
            'images.*'          => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ];
    }

    public function attributes()
    {
        return [
            'thumbnail'         => 'Thumbnail',
            'name'              => 'Nama',
            'about'             => 'Deskripsi',
            'headman'           => 'Kepala Desa',
            'people'            => 'Jumlah Penduduk',
            'agricultural_area' => 'Luas Pertanian',
            'total_area'        => 'Luas Total',
            'images'            => 'Gambar'
        ];
    }
}
