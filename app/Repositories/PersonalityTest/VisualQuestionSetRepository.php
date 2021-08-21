<?php

namespace App\Repositories\PersonalityTest;

use App\Models\PersonalityTest\VisualQuestionSet;
use App\Repositories\CoreRepository;

class VisualQuestionSetRepository extends CoreRepository
{
    /**
     * @param VisualQuestionSet $model
     *
     * @return void
     */
    public function __construct(VisualQuestionSet $model)
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
    public function findAllWithRelationships(array $filters = [])
    {
        return $this->getQuery()
            ->with('questions', 'answers')
            ->ordered()
            ->applyFilters($filters)
            ->get()
        ;
    }
}
