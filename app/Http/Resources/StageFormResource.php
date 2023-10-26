<?php

namespace App\Http\Resources;

use App\Models\Role;
use Illuminate\Http\Resources\Json\JsonResource;

class StageFormResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        try {
            $items = Role::with('permissions')->where('name', $this->stage->key)->first()?->permissions?->pluck('name');
            $actions = $items ? $items->map(function ($i) {
                return str_replace('-', ' ',  $i);
            }) : [];
        } catch (\Throwable $th) {
            $actions = [];
        }

        return [
            'id'   => $this->stage_id,
            'form_id'  => $this->form_id,
            'name' => $this->stage->name,
            'key'  => $this->stage->key,
            'actions' => $actions
        ];
    }
}
