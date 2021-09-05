<?php

namespace Azuriom\Plugin\Jirai\Requests;

use Azuriom\Http\Requests\Traits\ConvertCheckbox;
use Azuriom\Plugin\Jirai\Models\JiraiIssue;
use Illuminate\Foundation\Http\FormRequest;

class JiraiChangelogRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'description' => ['required'],
            'message' => ['required'],
            'issues.*' => ['required', 'integer', 'exists:jirai_issues,id'],
        ];
    }
}
