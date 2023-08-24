<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FormRequestResource extends JsonResource
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
            'id' => $this->id,
            'status' => $this->status,
            'message' => $this->message,
            'note' => $this->note,
            'form_id' => $this->form_id,
            'user_id' => $this->user_id,
            'form_request_number' => $this->form_request_number,
            'name' => $this->name,
            'form' => new FormResource($this->form),
            'form_page_item_fill' => $this->form_page_item_fill,
            'form_request_information' => $this->formRequestInformation,
            'user' => $this->user,
            'form_request_side' => $this->formRequestSide,
            'lastFormRequestInformation' => $this->lastFormRequestInformation,
            'formable' => $this->formable
        ];
    }
}
