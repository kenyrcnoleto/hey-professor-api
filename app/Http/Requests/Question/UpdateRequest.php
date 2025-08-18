<?php

namespace App\Http\Requests\Question;

use App\Rules\{OnlyAsDraft, WithQuestionMark};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
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

        //Policies - são portões - gates - dentro do laravel
        /** @var Question $question */
        $question = $this->route()->question; //@phpstan-ignore-line

        return Gate::allows('update', $question);
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
                new WithQuestionMark(),
                new OnlyAsDraft($this->route('question')),
                'min:10',
                // 'unique:questions,'.$this->route()->question->id,
                //Rule::unique('questions')->ignore($this->route()->question->id) //route()->question - encontra erro no larastan
                Rule::unique('questions', 'question')->ignore($this->route('question')->id), //@phpstan-ignore-line
            ],
        ];
    }
}
