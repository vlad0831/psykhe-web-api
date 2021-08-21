<?php

namespace App\Repositories\PersonalityTest;

use App\Models\PersonalityTest\VisualQuestionAnswer;
use App\Repositories\CoreRepository;

class VisualQuestionAnswerRepository extends CoreRepository
{
    /**
     * @param VisualQuestionAnswer $model
     *
     * @return void
     */
    public function __construct(VisualQuestionAnswer $model)
    {
        parent::__construct($model);
    }
}
