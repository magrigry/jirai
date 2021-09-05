<?php

namespace Azuriom\Plugin\Jirai\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JiraiTagRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'color' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'roles.*' => ['nullable', 'integer', 'exists:roles,id'],
        ];
    }
}
