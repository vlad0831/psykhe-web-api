<?php

namespace App\Repositories\PersonalityTest;

use App\Models\PersonalityTest\SurveyQuestion;
use App\Repositories\CoreRepository;

class SurveyQuestionRepository extends CoreRepository
{
    /**
     * @param SurveyQuestion $model
     *
     * @return void
     */
    public function __construct(SurveyQuestion $model)
    {
        parent::__construct($model);
    }

    /**
     * Retrieve all questions with eager loaded answers.
     *
     * @param array $filters
     *
     * @return mixed
     */
    public function findAllWithAnswers()
    {
        return $this->getQuery()
            ->with('answers')
            ->get()
        ;
    }
}
