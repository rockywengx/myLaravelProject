<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Knuckles\Scribe\Attributes\BodyParam;

class ApplicantsRequest  extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    // public function authorize(): bool
    // {
    //     return true;
    // }

    /**
     * 使用者輸入的資料驗證規則
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    #[BodyParam(name: 'first_name', description: 'first_name', required: true)]
    #[BodyParam(name: 'last_name', description: 'last_name', required: true)]
    #[BodyParam(name: 'email', description: 'email', required: true)]
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone_number' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.requieed' => 'first_name is required',
            'last_name.required' => 'last_name is required',
            'email.required' => 'email is required',
            'phone_number.required' => 'phone_number is required',
        ];
    }
}
