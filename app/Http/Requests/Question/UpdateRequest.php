<?php

namespace App\Http\Requests\Question;

use App\Rules\WithQuestionMark;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * @property-read string $question
     *
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
            'question' => [
                'required',
                 new WithQuestionMark,
                'min:10',
                // 'unique:questions,'.$this->route()->question->id,
                Rule::unique('questions')->ignore($this->route()->question->id)
                // Rule::unique('questions', 'question')->ignore($this->route('question')->id), //@phpstan-ignore-line
            ]
        ];
    }
}
