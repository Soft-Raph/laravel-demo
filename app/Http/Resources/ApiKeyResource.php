<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiKeyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'uuid' => $this->uuid,
            'app_id' => $this->app_id,
            'base_url' => $this->base_url,
            'description' => $this->description,
            'service' => $this->service,
            'username' => $this->username,
            'public_key' => $this->public_key,
            'private_key' => $this->private_key,
            'created_at' => $this->created_at
        ];
    }
}
