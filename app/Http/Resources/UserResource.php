<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);
        if ($data['role'] == 'user') {
            $data = [
                'id' => $data['id'],
                'name' => $data['name'],
                'email' => $data['email'],
                'photo' => $data['photo'],
            ];

        }
        return $data;
    }
}
