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
        $method = strtoupper($this->method());
        $POST_RULES = [
            'title' => 'bail|required',
            'contents' => 'bail|required'
        ];

        $PUT_RULES = array_merge($POST_RULES, ['id' => 'bail|required|exists:posts,id']);
        if($method == 'POST'){
            return $POST_RULES;
        }
        return $PUT_RULES;

    }


}
