<?php

namespace App\Http\Requests\Message;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // L'autorisation est gérée par le middleware auth:api
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'receiver_id.required' => 'Le destinataire est obligatoire.',
            'receiver_id.exists' => 'Le destinataire sélectionné n\'existe pas.',
            'content.required' => 'Le contenu du message ne peut pas être vide.',
            'content.string' => 'Le contenu du message doit être du texte.',
            'content.max' => 'Le message ne peut pas dépasser :max caractères.',
        ];
    }
}
