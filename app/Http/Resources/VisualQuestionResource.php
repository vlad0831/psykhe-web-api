<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VisualQuestionResource extends JsonResource
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
            'id'        => $this->id,
            'text'      => $this->text,
            'maximum'   => $this->maximum,
            'link_url'  => $this->link_url,
            'link_text' => $this->link_text,
            'image'     => $this->has_image ? $this->image : null,

            'responses' => $this->whenLoaded('responses', function () {
                return VisualResponseResource::collection($this->responses);
            }),
        ];
    }
}
