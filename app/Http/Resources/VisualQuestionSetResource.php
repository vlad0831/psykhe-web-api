<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VisualQuestionSetResource extends JsonResource
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
            'id'      => $this->id,
            'text'    => $this->text,
            'heading' => $this->heading,
            'maximum' => $this->maximum,
            'mutable' => $this->mutable,

            'questions' => $this->whenLoaded('questions', function () {
                return VisualQuestionResource::collection($this->questions);
            }),
            'answers' => $this->whenLoaded('answers', function () {
                return VisualQuestionAnswerResource::collection($this->answers);
            }),
        ];
    }
}
