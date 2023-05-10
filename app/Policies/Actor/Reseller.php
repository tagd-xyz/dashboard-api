<?php

namespace App\Policies\Actor;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Tagd\Core\Models\Actor\Actor as ActorModel;
use Tagd\Core\Models\Actor\Reseller as ResellerModel;
use Tagd\Core\Models\User\User;

class Reseller
{
    use HandlesAuthorization; // HandlesGenericUsers;

    /**
     * Determine whether the user can update details.
     *
     * @return mixed
     */
    public function update(User $user, ResellerModel $reseller, ActorModel $actor)
    {
        // TODO: check ownership
        return Response::allow();
    }
}
