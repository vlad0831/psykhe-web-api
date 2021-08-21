<?php

namespace App\Http\Requests\Savelist;

use Illuminate\Foundation\Http\FormRequest;

class CreateSavelistRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
            ],
        ];
    }
}
