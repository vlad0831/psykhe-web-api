<?php

namespace App\Http\Requests\PersonalityTest;

use App\Http\Requests\Request;

class SurveyQuestionResponseStoreRequest extends Request
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
            'responses'                             => 'required|array',
            'responses.*.survey_question_id'        => 'required|integer|exists:survey_questions,id',
            'responses.*.survey_question_answer_id' => 'required|integer|exists:survey_question_answers,id',
        ];
    }
}
