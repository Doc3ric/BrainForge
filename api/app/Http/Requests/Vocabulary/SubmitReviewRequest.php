<?php

namespace App\Http\Requests\Vocabulary;

use Illuminate\Foundation\Http\FormRequest;

class SubmitReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Auth handled by middleware/controller
    }

    public function rules(): array
    {
        return [
            'quality_score' => ['required', 'integer', 'min:0', 'max:5'],
            'idempotency_key' => ['required', 'uuid'],
        ];
    }
}
