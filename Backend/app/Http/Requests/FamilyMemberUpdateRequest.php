<?php

namespace App\Http\Requests;

use App\Models\FamilyMember;
use Illuminate\Foundation\Http\FormRequest;

class FamilyMemberUpdateRequest extends FormRequest
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
            'name'              => 'required|string',
            'email'             => 'required|email|max:255|unique:users,email,' . FamilyMember::find($this->route('family_member'))->user_id,
            'password'          => 'required|string|min:8',
            'head_of_family_id' => 'required|string|exists:head_of_families,id',
            'profile_picture'   => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'identity_number'   => 'required|integer',
            'gender'            => 'required|string|in:male,female',
            'birth_date'        => 'required|date',
            'phone_number'      => 'required|string',
            'occupation'        => 'required|string',
            'marital_status'    => 'required|string|in:married,single',
            'relation'          => 'required|string|in:wife,child,husband',
        ];
    }

    public function attributes()
    {
        return [
            'name'              => 'Name',
            'email'             => 'Email',
            'password'          => 'Kata Sandi',
            'head_of_family_id' => 'Kepala Keluarga',
            'profile_picture'   => 'Foto Profil',
            'identity_number'   => 'Nomor Identitas',
            'gender'            => 'Jenis Kelamin',
            'birth_date'        => 'Tanggal Lahir',
            'phone_number'      => 'Nomor Telp',
            'occupation'        => 'Pekerjaan',
            'marital_status'    => 'Status Perkawinan',
            'relation'          => 'Hubungan'
        ];
    }

    public function messages()
    {
        return [
            'required'     => ':attribute harus diisi',
            'string'       => ':attribute harus berupa string',
            'max'          => ':attribute maksimal :max karakter',
            'unique'       => ':attribute sudah ada',
            'image'        => ':attribute harus berupa gambar',
            'email'        => ':attribute harus berupa email',
            'min'          => ':attribute minimal :min karakter',
            'exists'       => ':attribute tidak ditemukan',
            'integer'      => ':attribute harus berupa angka',
            'array'        => ':attribute harus berupa array',
            'mimes'        => ':attribute harus berupa gambar',
            'max:2048'     => ':attribute maksimal 2048 KB',
            'unique:users' => ':attribute sudah ada',
            'in'           => ':attribute harus berupa salah satu dari :values'
        ];
    }
}
