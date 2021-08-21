<?php

namespace App\Models\PersonalityTest;

use App\Models\User\PersonalityTest\VisualResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;

class VisualQuestion extends Model
{
    protected $fillable = [
        'code',
        'text',
        'order',
        'maximum',
        'link_url',
        'link_text',
        'has_image',
        'visual_question_set_id',
    ];

    protected $casts = [
        'has_image' => 'boolean',
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

    public function getImageAttribute()
    {
        return [
            'pool'        => 'pt',
            'key'         => "{$this->set->code}/{$this->code}",
            'constraints' => [
                'width'        => 75,
                'height'       => 100,
                'content-type' => 'image/jpeg',
            ],
        ];
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
        return $this->hasMany(VisualResponse::class, 'visual_question_id');
    }
}
