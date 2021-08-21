<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SurveyQuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'   => $this->id,
            'text' => $this->text,

            'answers' => $this->whenLoaded('answers', function () {
                return SurveyQuestionAnswerResource::collection($this->answers);
            }),
            'responses' => $this->whenLoaded('responses', function () {
                return SurveyResponseResource::collection($this->responses);
            }),
        ];
    }
}
