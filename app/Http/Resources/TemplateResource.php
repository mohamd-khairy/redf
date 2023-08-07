<?php

namespace App\Http\Resources;

use App\Models\Organization;
use App\Models\Template;
use App\Models\User;
use App\Models\UserTemplate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use DB;

class TemplateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'ar_name' => $this->ar_name,
            'icon' => $this->icon,
            'organizations' => $this->organizations,
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
            'user' => $this->user,
            'pages' => TemplatePageResource::collection($this->pages),
            'deleted_at' => $this->deleted_at,
            'requests_count' => DB::table('forms')->where('template_id', $this->id)->count()
        ];
    }
}
