<?php

namespace App\Repositories\User\PersonalityTest;

use App\Models\User\PersonalityTest\VisualResponse;

class VisualResponseRepository extends BaseResponseRepository
{
    /**
     * @param VisualResponse $model
     *
     * @return void
     */
    public function __construct(VisualResponse $model)
    {
        parent::__construct($model);
    }
}
