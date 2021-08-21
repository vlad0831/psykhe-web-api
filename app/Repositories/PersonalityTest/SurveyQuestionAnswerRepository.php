<?php

namespace App\Repositories\PersonalityTest;

use App\Models\PersonalityTest\SurveyQuestionAnswer;
use App\Repositories\CoreRepository;

class SurveyQuestionAnswerRepository extends CoreRepository
{
    /**
     * @param SurveyQuestionAnswer $model
     *
     * @return void
     */
    public function __construct(SurveyQuestionAnswer $model)
    {
        parent::__construct($model);
    }
}
