<?php

namespace App\Repositories\User\PersonalityTest;

use App\Models\User\PersonalityTest\SurveyResponse;

class SurveyResponseRepository extends BaseResponseRepository
{
    /**
     * @param SurveyResponse $model
     *
     * @return void
     */
    public function __construct(SurveyResponse $model)
    {
        parent::__construct($model);
    }
}
