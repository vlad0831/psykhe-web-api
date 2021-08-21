<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssetRequest extends FormRequest
{
    /**
     * @var string
     */
    public $pool;

    /**
     * @var string
     */
    public $key;

    /**
     * @var array
     */
    public $constraints = [];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->pool        = $this->input('pool');
        $this->key         = $this->input('key');
        $this->constraints = $this->input('constraints');

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'pool' => [
                'required',
                'string',
            ],
            'key' => [
                'required',
                'string',
            ],
            'constraints.*' => [
                'required',
                'array',
            ],
            'constraints.width' => [
                'nullable',
                'numeric',
            ],
            'constraints.height' => [
                'nullable',
                'numeric',
            ],
            'constraints.scale' => [
                'nullable',
                'numeric',
            ],
            'constraints.content-type' => [
                'nullable',
                'string',
            ],
        ];
    }
}
