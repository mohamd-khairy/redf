<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'   => $this->id,
            // 'form_id'  => $this->form_id,
            'name' => $this->name,
            'key'  => $this->key,
            'applications' => ApplicationResource::collection($this->applications)
        ];
    }
}
