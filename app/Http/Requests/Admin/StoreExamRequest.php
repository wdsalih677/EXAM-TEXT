<?php

namespace App\Http\Requests\Admin;

use App\Concerns\ValidatesExamQuestions;
use App\Models\Exam;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreExamRequest extends FormRequest
{
    use ValidatesExamQuestions;

    public function authorize(): bool
    {
        return $this->user()?->can('create', Exam::class) ?? false;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->examRules();
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return $this->examAttributes();
    }
}
