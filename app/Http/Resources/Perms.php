<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\Permission\Models\Role;

class Perms extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'perms' => Role::findById($this->id)->permissions(),
        ];
    }
}