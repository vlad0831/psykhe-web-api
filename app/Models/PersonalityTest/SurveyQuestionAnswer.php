<?php

namespace App\Models\PersonalityTest;

use App\Models\User\PersonalityTest\SurveyResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SurveyQuestionAnswer extends Model
{
    /**
     * Get the question relationship.
     *
     * @return BelongsTo
     */
    public function question()
    {
        return $this->belongsTo(SurveyQuestion::class, 'survey_question_id');
    }

    /**
     * Get the responses relationship.
     *
     * @return HasMany
     */
    public function responses()
    {
        return $this->hasMany(SurveyResponse::class, 'survey_question_answer_id');
    }
}
