<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreCVRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function attributes()   {
        return [
            'cv_file' => 'fichier CV'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'cv_file' => 'required|file|mimes:pdf,docx|max:5120'
        ];
    }

    public function messages()
    {
        return [
            'cv_file.required' => 'le :attribute est obligatoire.',
            'cv_file.mimes' => 'Le :attribute doit être un fichier de type :values.',
            'cv_file.max' => 'La taille maximale du :attribute est de :max kilobytes.',
            'title.required' => 'Le titre du CV est obligatoire.',
            'title.max' => 'Le titre du CV ne doit pas dépasser :max caractères.',
        ];
    }
}
