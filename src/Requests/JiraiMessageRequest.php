<?php

namespace Azuriom\Plugin\Jirai\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JiraiMessageRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'message' => ['required'],
            'jirai_issue_id' => ['required', 'integer', 'exists:jirai_issues,id'],
        ];
    }
}
