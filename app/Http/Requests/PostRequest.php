<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
            'user_id' => 'required|integer|exists:applicants,id',
            'title' => 'required|string|max:255',
            'content' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'User ID is required',
            'title.required' => 'Title is required',
            'content.required' => 'Content is required',
        ];
    }
}
