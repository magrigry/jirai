<?php

namespace Azuriom\Plugin\Jirai\Requests;

use Azuriom\Http\Requests\Traits\ConvertCheckbox;
use Azuriom\Plugin\Jirai\Models\JiraiIssue;
use Illuminate\Foundation\Http\FormRequest;

class JiraiIssueRequest extends FormRequest
{
    use ConvertCheckbox;

    /**
     * The checkboxes attributes.
     *
     * @var array
     */
    protected $checkboxes = [
        'closed',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => ['required', 'string', 'max:100'],
            'message' => ['required', 'string'],
            'closed' => ['filled', 'boolean'],
            'type' => [sprintf('in:%s,%s', JiraiIssue::TYPE_BUG, JiraiIssue::TYPE_SUGGESTION)],
            'tags.*' => ['required', 'integer', 'exists:jirai_tags,id'],
        ];
    }
}
