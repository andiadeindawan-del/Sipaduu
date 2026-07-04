<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:100',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'kontak' => ['string', 'max:20', 'nullable'],
            'nama_usaha' => ['string', 'max:100', 'nullable'],
            'nib' => [
                'string',
                'max:30',
                'nullable',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'jenis_usaha' => ['in:formal,non_formal', 'nullable'],
            'alamat_lengkap' => ['string', 'max:500', 'nullable'],
        ];
    }
}
