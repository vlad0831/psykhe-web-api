<?php

namespace App\Models\PersonalityTest;

use App\Models\User\PersonalityTest\SurveyResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;

class VisualQuestionAnswer extends Model
{
    protected $fillable = [
        'code',
        'text',
        'order',
    ];

    /**
     * Sort the query by ascending order.
     *
     * @param Builder $query
     */
    public function scopeOrdered(Builder $query)
    {
        if (! Schema::hasColumn($this->getTable(), 'order')) {
            return;
        }

        $query->orderBy('order');
    }

    /**
     * Get the set relationship.
     *
     * @return BelongsTo
     */
    public function set()
    {
        return $this->belongsTo(VisualQuestionSet::class, 'visual_question_set_id');
    }

    /**
     * Get the responses relationship.
     *
     * @return HasMany
     */
    public function responses()
    {
        return $this->hasMany(SurveyResponse::class, 'visual_question_answer_id');
    }
}
