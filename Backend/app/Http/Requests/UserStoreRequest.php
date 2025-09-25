<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Kata Sandi'
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute harus diisi',
            'string' => ':attribute harus berupa string',
            'max' => ':attribute maksimal :max karakter',
            'min' => ':attribute minimal :min karakter',
            'email' => ':attribute harus berupa email',
            'unique' => ':attribute sudah ada',
        ];
    }
}
