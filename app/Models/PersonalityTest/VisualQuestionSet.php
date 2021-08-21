<?php

namespace App\Models\PersonalityTest;

use App\Models\User\PersonalityTest\VisualResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;

class VisualQuestionSet extends Model
{
    protected $fillable = [
        'mutable',
        'maximum',
        'heading',
        'order',
        'code',
        'text',
    ];

    protected $casts = [
        'mutable' => 'boolean',
    ];

    /**
     * Apply filters to the query.
     *
     * @param Builder $query
     * @param array   $filters
     */
    public function scopeApplyFilters(Builder $query, array $filters)
    {
        if ($mutable = $filters['mutable'] ?? false) {
            $query->where('mutable', true);
        }
    }

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
     * Get the answers relationship.
     *
     * @return HasMany
     */
    public function questions()
    {
        return $this->hasMany(VisualQuestion::class, 'visual_question_set_id')->ordered();
    }

    /**
     * Get the answers relationship.
     *
     * @return HasMany
     */
    public function answers()
    {
        return $this->hasMany(VisualQuestionAnswer::class, 'visual_question_set_id')->ordered();
    }

    /**
     * Get the responses relationship.
     *
     * @return HasMany
     */
    public function responses()
    {
        return $this->hasManyThrough(
            VisualResponse::class,
            VisualQuestion::class,
            'visual_question_set_id',
            'visual_question_id',
        );
    }
}
