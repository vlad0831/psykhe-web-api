<?php

namespace App\Http\Resources;

use App\Models\User\Profile;
use App\Models\User\Traits;
use App\Services\Profile\UserProfileAvatarService;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Traits  traits
 * @property Profile profile
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'identifier' => $this->identifier,
            'name'       => [
                'name_first' => $this->profile->name_first,
                'name_last'  => $this->profile->name_last,
                'name_full'  => $this->profile->name_first.' '.$this->profile->name_last,
            ],
            'profile' => [
                'dob'            => $this->profile->dob,
                'has_avatar'     => UserProfileAvatarService::userHasAvatar($this->resource),
                'avatar_uploded' => strtotime($this->profile->avatar_uploaded_at),
            ],
            'referrals' => ReferralResource::collection($this->referrals),
            'nags'      => [
                'profile' => $this->profile_nagged,
            ],
            'account_valid'                  => (bool) $this->email_verified_at,
            'traits'                         => $this->traits ? $this->traits : $this->traits()->firstOrNew([]),
            'population_distance_percentage' => $this->traits ? $this->traits->getPopulationDistancePercentishAttribute() : null,
            'survey_complete'                => $this->pt_transmitted || $this->unansweredSurveyQuestions()->get()->isEmpty(),
            'visual_complete'                => $this->pt_transmitted || $this->unansweredVisualQuestionSets()->get()->isEmpty(),
            'pt_transmitted'                 => $this->pt_transmitted,
            'pt_complete'                    => $this->pt_complete,
        ];
    }
}
