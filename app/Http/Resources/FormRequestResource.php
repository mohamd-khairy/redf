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
            'category' => $this->category ?? null,
            'case_type' => $this->case_type,
            'name' => $this->name,
            'form' => new FormResource($this->form),
            'form_page_item_fill' => $this->form_page_item_fill,
            'form_request_informations' => $this->formRequestInformations,
            'user' => $this->user,
            'form_request_side' => $this->formRequestSide,
            'lastFormRequestInformation' => $this->lastFormRequestInformation,
            'request' => $this->case,
            'form_request_number' => $this->form_request_number,
            'branche_id' => $this->branche_id,
            'branche' => $this->branche,
            'form_type' => $this->form_type,
            'case_date' => $this->case_date,
            'specialization_id' => $this->specialization_id,
            'specialization' => $this->specialization,
            'organization_id' => $this->organization_id,
            'organization' => $this->organization,
            'case' => $this->case,
            'formAssignedRequests' => $this->formAssignedRequests
        ];
    }
}
