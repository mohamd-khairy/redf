<?php

namespace App\Http\Resources;

use App\Models\Permission;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $permissions = DB::table('role_has_permissions')->select('permissions.id', 'permissions.name')
            ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
            ->whereIn('role_id', $this->roles->pluck('id'))
            ->distinct()
            ->pluck('name');

        return [
            "id" => $this->id ??  null,
            "name" =>  $this->name ?? null,
            "avatar" => $this->avatar ?? null,
            "phone" => $this->phone ?? null,
            "email" => $this->email ?? null,
            "email_verified_at" => $this->email_verified_at ?? null,
            "status" => $this->status ?? null,
            "roles" => JsonDataResource::collection($this->roles) ?? null,
            'permissions' => $permissions ?? null,
        ];
    }
}
