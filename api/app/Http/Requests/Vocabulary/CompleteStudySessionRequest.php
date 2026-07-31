<?php

namespace App\Http\Requests\Vocabulary;

use Illuminate\Foundation\Http\FormRequest;

class CompleteStudySessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'string', 'in:completed'],
            'word_ids' => ['required', 'array', 'min:1'],
            'word_ids.*' => ['required', 'uuid', 'exists:vocabulary_words,id'],
        ];
    }
}
