<?php

namespace App\Models\User\PersonalityTest;

use App\Models\PersonalityTest\SurveyQuestion;
use App\Models\PersonalityTest\SurveyQuestionAnswer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyResponse extends Model
{
    protected $table = 'user_survey_responses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'survey_question_id',
        'survey_question_answer_id',
    ];

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
     * Get the answer relationship.
     *
     * @return BelongsTo
     */
    public function answer()
    {
        return $this->belongsTo(SurveyQuestionAnswer::class, 'survey_question_answer_id');
    }
}
