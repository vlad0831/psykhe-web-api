<?php

namespace App\Http\Requests\PersonalityTest;

use App\Http\Requests\Request;

class VisualQuestionResponseStoreRequest extends Request
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
            'responses'                             => 'present|array',
            'responses.*.visual_question_id'        => 'required|integer|exists:visual_questions,id',
            'responses.*.visual_question_answer_id' => 'nullable|integer|exists:visual_question_answers,id',
        ];
    }
}
