<?php

namespace App\Models;

use App\Models\PersonalityTest\SurveyQuestion;
use App\Models\PersonalityTest\VisualQuestionSet;
use App\Models\User\PersonalityTest\SurveyResponse;
use App\Models\User\PersonalityTest\VisualResponse;
use App\Models\User\Profile;
use App\Models\User\Referral;
use App\Models\User\Savelist;
use App\Models\User\Traits;
use Carbon\Carbon;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property string  email
 * @property Profile profile
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory;
    use Notifiable;
    use HasApiTokens;
    use MustVerifyEmailTrait;

    const EMAIL_VERIFICATION_TOKEN_VALIDATION_DAYS_PERIOD = 2;

    const PASSWORD_RESET_TOKEN_VALIDATION_MINUTES_PERIOD = 60;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_preview',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $visible = [
        'identifier',
        'traits',
        'email_verified_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'pt_transmitted'    => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($user) {
            if (! isset($user->attributes['identifier']) && $user->email) {
                $user->attributes['identifier'] = $user->getIdentifierAttribute();
                $user->attributes['password'] = bcrypt($user->attributes['password']);
            }
        });

        self::updating(function ($user) {
            if ($user->isDirty('password')) {
                $user->attributes['password'] = bcrypt($user->attributes['password']);
            }
        });
    }

    public function getFormattedSurveyResponsesAttribute()
    {
        return $this->surveyResponses->mapWithKeys(function ($response) {
            return [
                $response->question->code => $response->answer->score,
            ];
        });
    }

    public function getFormattedVisualResponsesAttribute()
    {
        return $this->getVisualResponses(false);
    }

    public function getFormattedMutableVisualResponsesAttribute()
    {
        return $this->getVisualResponses(true);
    }

    public function getVisualResponses($onlyMutable = false)
    {
        $responses = $this->visualResponses()
            ->when($onlyMutable, function (Builder $query) {
                $query->whereHas('question.set', function (Builder $query) {
                    $query->where('mutable', 1);
                });
            })
            ->get()
            ->mapToGroups(function ($response) {
                $key = $response->question->set->code;

                if ($response->answer) {
                    $key .= "/{$response->question->code}";
                }

                return [
                    $key => $response->answer ? $response->answer->code : $response->question->code,
                ];
            })
        ;

        foreach (\App\Models\PersonalityTest\VisualQuestionSet::all() as $visualQuestionSet) {
            if ($onlyMutable && ! $visualQuestionSet->mutable) {
                continue;
            }

            $hasAnswers = (bool) $visualQuestionSet->answers->count();
            foreach ($visualQuestionSet->questions as $visualQuestion) {
                $key = $visualQuestionSet->code;

                if ($hasAnswers) {
                    $key .= "/{$visualQuestion->code}";

                    if (! isset($responses[$key])) {
                        // FIXME BAD CODE. instead, question sets should have a "default" answer per-set
                        // at the moment, there is only one set, which is known, so we can get away with hard-coding
                        // "sometimes" for now.
                        $responses[$key] = ['sometimes'];
                    }
                } else {
                    if (! isset($responses[$key])) {
                        $responses[$key] = [];
                    }
                }
            }
        }

        return $responses;
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function traits()
    {
        return $this->hasOne(Traits::class);
    }

    public function referrals()
    {
        return $this->hasMany(Referral::class);
    }

    public function surveyResponses()
    {
        return $this->hasMany(SurveyResponse::class);
    }

    public function visualResponses()
    {
        return $this->hasMany(VisualResponse::class);
    }

    public function unansweredSurveyQuestions()
    {
        return SurveyQuestion::whereDoesntHave('responses', function ($query) {
            $query->where('user_id', $this->id);
        });
    }

    public function unansweredVisualQuestionSets()
    {
        return VisualQuestionSet::whereDoesntHave('responses', function ($query) {
            $query->where('user_id', $this->id);
        });
    }

    public function savelists()
    {
        return $this->hasMany(Savelist::class, 'user_id');
    }

    public function getIdentifierAttribute()
    {
        return $this->attributes['identifier'] ?? md5($this->email);
    }

    public function getPtCompleteAttribute()
    {
        return $this->pt_transmitted || ($this->traits || ! $this->traits()->get()->isEmpty()) || ($this->unansweredSurveyQuestions()->get()->isEmpty() && $this->unansweredVisualQuestionSets()->get()->isEmpty());
    }

    public function isActivationTokenValid(string $timestamp)
    {
        return Carbon::createFromTimestamp($timestamp)->diffInDays(now()) <= self::EMAIL_VERIFICATION_TOKEN_VALIDATION_DAYS_PERIOD;
    }

    public function isPasswordResetTokenValid(string $timestamp)
    {
        return Carbon::createFromTimestamp($timestamp)->diffInMinutes(now()) <= self::PASSWORD_RESET_TOKEN_VALIDATION_MINUTES_PERIOD;
    }
}
