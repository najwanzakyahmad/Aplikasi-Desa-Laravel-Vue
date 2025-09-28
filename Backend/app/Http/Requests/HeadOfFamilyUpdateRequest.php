<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HeadOfFamilyUpdateRequest extends FormRequest
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
            'name' => 'required|string',
            'password' => 'nullable|string|min:8',
            'profile_picture' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'identity_number' => 'required|integer',
            'gender' => 'required|string|in:male,female',
            'birth_date' => 'required|date',
            'phone_number' => 'required|string',
            'occupation' => 'required|string',
            'marital_status' => 'required|string|in:married,single',
        ];
    }

    public function attributes()
    {
        return [
            'name'              => 'Name',
            'password'          => 'Kata Sandi',
            'profile_picture'   => 'Foto Profil',
            'identity_number'   => 'Nomor Identitas',
            'gender'            => 'Jenis Kelamin',
            'birth_date'        => 'Tanggal Lahir',
            'phone_number'      => 'Nomor Telp',
            'occupation'        => 'Pekerjaan',
            'marital_status'    => 'Status Perkawinan'
        ];
    }
}
