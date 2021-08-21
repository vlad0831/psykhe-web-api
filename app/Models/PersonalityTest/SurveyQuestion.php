<?php

namespace App\Models\PersonalityTest;

use App\Models\User\PersonalityTest\SurveyResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SurveyQuestion extends Model
{
    /**
     * Get the answers relationship.
     *
     * @return HasMany
     */
    public function answers()
    {
        return $this->hasMany(SurveyQuestionAnswer::class, 'survey_question_id');
    }

    /**
     * Get the responses relationship.
     *
     * @return HasMany
     */
    public function responses()
    {
        return $this->hasMany(SurveyResponse::class, 'survey_question_id');
    }
}
