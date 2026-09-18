<?php

namespace App\Http\Requests\Trainee;

use App\Models\Attempt;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SubmitAnswerRequest extends FormRequest
{
    public function authorize(): bool
    {
        $attempt = $this->route('attempt');

        return $attempt instanceof Attempt
            && ($this->user()?->can('answer', $attempt) ?? false);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'option_id' => ['required', 'integer', 'exists:options,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'option_id' => 'الإجابة',
        ];
    }
}
