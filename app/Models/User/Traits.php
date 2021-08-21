<?php

namespace App\Models\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Traits extends Model
{
    protected $table = 'user_traits';

    protected $fillable = [
        'user_id',
        'ocean',
    ];

    protected $visible = [
        'ocean',
    ];

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @param null $value
     */
    public function setOceanAttribute($value = null)
    {
        if ($value !== null) {
            $this->attributes['ocean'] = $value;

            return;
        }

        if (! $this->user->unansweredSurveyQuestions()->get()->isEmpty()) {
            $this->attributes['ocean'] = null;

            return;
        }

        $user = $this->user()
            ->with('surveyResponses', 'surveyResponses.question', 'surveyResponses.answer')
            ->first()
        ;

        $traits = [];
        foreach ($user->surveyResponses as $response) {
            $quality            = $response->question->quality;
            $score              = $response->answer->score;
            $traits[$quality][] = $score;
        }

        foreach ($traits as $quality => $scores) {
            $sum = 0;
            foreach ($scores as $score) {
                $sum += $score;
            }

            $traits[$quality] = bcdiv($sum, count($scores), 4);
        }

        $this->attributes['ocean'] = json_encode($traits);
    }

    /**
     * @param $n
     *
     * @return string|null
     */
    protected static function round($n)
    {
        return bcdiv(bcadd($n, '0.5', 1), '1.0', 0);
    }

    /**
     * @return mixed|null
     */
    public function getOceanScoresAttribute()
    {
        if (! array_key_exists('ocean', $this->attributes) || ! $this->attributes['ocean']) {
            $this->ocean = null;

            if (! array_key_exists('ocean', $this->attributes) || ! $this->attributes['ocean']) {
                return null;
            }
        }

        return json_decode($this->attributes['ocean'], true);
    }

    /**
     * @return array|null
     */
    public function getOceanRoundedAttribute()
    {
        if (! $this->ocean_scores) {
            return null;
        }
        $traits = [];
        foreach ($this->ocean_scores as $quality => $score) {
            $traits[$quality] = static::round($this->ocean_scores[$quality]);
        }

        return $traits;
    }

    public function getOceanPercentageAttribute()
    {
        if (! $this->ocean_scores) {
            return null;
        }
        $traits = [];
        foreach ($this->ocean_scores as $quality => $score) {
            $traits[$quality] = bcmul(
                bcadd(
                    bcmul(
                        bcdiv(
                            bcsub(
                                $score,
                                '1.0',
                                4
                            ),
                            '4.0',
                            4
                        ),
                        '0.8',
                        4
                    ),
                    '0.1',
                    4
                ),
                '100.0',
                4
            );
        }

        return $traits;
    }

    public function getOceanShortAttribute()
    {
        if (! $this->ocean_scores) {
            return null;
        }

        return implode('', [
            'O', static::round($this->ocean_scores['O']),
            'C', static::round($this->ocean_scores['C']),
            'E', static::round($this->ocean_scores['E']),
            'A', static::round($this->ocean_scores['A']),
            'N', static::round($this->ocean_scores['N']),
        ]);
    }

    public function getOceanHumanAttribute()
    {
        if (! $this->ocean_percentage) {
            return null;
        }
        $traits = [];
        foreach ($this->ocean_rounded as $quality => $score) {
            $traits[$quality] = 'low';

            if ($score > 1) {
                $traits[$quality] = 'moderate-low';
            }

            if ($score > 2) {
                $traits[$quality] = 'moderate';
            }

            if ($score > 3) {
                $traits[$quality] = 'moderate-high';
            }

            if ($score > 4) {
                $traits[$quality] = 'high';
            }
        }

        return $traits;
    }

    public function getOceanAttribute()
    {
        $ocean_scores = $this->ocean_scores;

        if (! $ocean_scores) {
            return null;
        }

        return [
            'scores'     => $ocean_scores,
            'rounded'    => $this->ocean_rounded,
            'short'      => $this->ocean_short,
            'percentage' => $this->ocean_percentage,
            'human'      => $this->ocean_human,
        ];
    }

    protected function getPopulationDistanceAttribute()
    {
        $ocean = [];
        foreach (self::all() as $traits) {
            foreach ($traits->ocean_rounded as $trait => $score) {
                if (! isset($ocean[$trait])) {
                    $ocean[$trait] = [
                        'count' => 0,
                        'total' => 0,
                    ];
                }
                $ocean[$trait]['count']++;
                $ocean[$trait]['total'] = $score;
            }
        }

        $averages = [];
        foreach ($ocean as $trait => $data) {
            if ($data['count'] === 0) {
                return null;
            }
            $averages[$trait] = $data['total'] / $data['count'];
        }

        $distances = [];
        foreach ($this->ocean_rounded as $trait => $score) {
            if (! isset($averages[$trait])) {
                return null;
            }
            $distances[$trait] = ($averages[$trait] - $score);
        }

        $squares = [];
        foreach ($distances as $trait => $distance) {
            $squares[$trait] = $distance * $distance;
        }

        $sum = 0;
        foreach ($squares as $square) {
            $sum += $square;
        }

        return sqrt($sum);
    }

    public function getPopulationDistancePercentishAttribute()
    {
        $max      = sqrt(((4 * 4) * 5));
        $distance = $this->population_distance;

        return (($max - $distance) / $max) * 100.0;
    }
}
