<?php

namespace App\Http\V1\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Tagd\Core\Models\User\Role;

class Me extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function toArray($request): array
    {
        $actors = $this
            ->actors()
            ->map(function ($actor) {
                // dd($actor->avatar_small_url);
                return [
                    'type' => strtolower(basename(str_replace('\\', '/', get_class($actor)))),
                    'id' => $actor->id,
                    'name' => $actor->name,
                    'avatarUploadId' => $actor->avatar_upload_id,
                    'logoUrl' => $actor->avatar_url,
                    'logoSmallUrl' => $actor->avatar_small_url,
                    'website' => $actor->website,
                ];
            })
            ->filter(function ($actor) {
                return Role::RESELLER == $actor['type'];
            });

        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'actors' => $actors->toArray(),
        ];
    }
}
