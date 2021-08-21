<?php

namespace App\Models\User\PersonalityTest;

use App\Models\PersonalityTest\VisualQuestion;
use App\Models\PersonalityTest\VisualQuestionAnswer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisualResponse extends Model
{
    protected $table = 'user_visual_responses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'visual_question_id',
        'visual_question_answer_id',
    ];

    public function setVisualQuestionAnswerIdAttribute($value)
    {
        $this->attributes['visual_question_answer_id'] = $value ?? null;
    }

    /**
     * Get the question relationship.
     *
     * @return BelongsTo
     */
    public function question()
    {
        return $this->belongsTo(VisualQuestion::class, 'visual_question_id');
    }

    /**
     * Get the answer relationship.
     *
     * @return BelongsTo
     */
    public function answer()
    {
        return $this->belongsTo(VisualQuestionAnswer::class, 'visual_question_answer_id');
    }
}
