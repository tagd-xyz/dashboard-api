<?php

namespace App\Policies\Item;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Tagd\Core\Models\Actor\Actor as ActorModel;
use Tagd\Core\Models\Item\Tagd as TagdModel;
use Tagd\Core\Models\User\User;

class Tagd
{
    use HandlesAuthorization; // HandlesGenericUsers;

    /**
     * Determine whether the user can list.
     *
     * @return mixed
     */
    public function index(User $user, ActorModel $actor)
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can store.
     *
     * @return mixed
     */
    public function store(User $user, ActorModel $actor)
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can show details.
     *
     * @return mixed
     */
    public function show(User $user, TagdModel $tagd, ActorModel $actor)
    {
        return $tagd->reseller_id == $actor->id
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can destroy.
     *
     * @return mixed
     */
    public function destroy(User $user, TagdModel $tagd, ActorModel $actor)
    {
        return $tagd->reseller_id == $actor->id
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can confirm.
     *
     * @return mixed
     */
    public function confirm(User $user, TagdModel $tagd, ActorModel $actor)
    {
        return $tagd->reseller_id == $actor->id
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can cancel.
     *
     * @return mixed
     */
    public function cancel(User $user, TagdModel $tagd, ActorModel $actor)
    {
        return $tagd->reseller_id == $actor->id
            ? Response::allow()
            : Response::deny();
    }
}
