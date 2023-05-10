<?php

namespace App\Policies\Item;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Tagd\Core\Models\Actor\Actor as ActorModel;
use Tagd\Core\Models\Actor\Consumer as ConsumerModel;
use Tagd\Core\Models\Actor\Reseller as ResellerModel;
use Tagd\Core\Models\Actor\Retailer as RetailerModel;
use Tagd\Core\Models\Item\Item as ItemModel;
use Tagd\Core\Models\User\User;

class Item
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

    // /**
    //  * Determine whether the user can show details.
    //  *
    //  * @param  User  $user
    //  * @param  ItemModel  $item
    //  * @param  ActorModel  $actor
    //  * @return mixed
    //  */
    // public function show(User $user, ItemModel $item, ActorModel $actor)
    // {
    //     // TODO
    //     switch (get_class($actor)) {
    //         case RetailerModel::class:
    //             break;
    //         case ResellerModel::class:
    //             break;
    //         case ConsumerModel::class:
    //             break;
    //     }

    //     return Response::allow();
    // }

    // /**
    //  * Determine whether the user can store.
    //  *
    //  * @param  User  $user
    //  * @param  ActorModel  $actor
    //  * @return mixed
    //  */
    // public function store(User $user, ActorModel $actor)
    // {
    //     // TODO
    //     switch (get_class($actor)) {
    //         case RetailerModel::class:
    //             return Response::allow();
    //             break;
    //         case ResellerModel::class:
    //         case ConsumerModel::class:
    //         default:
    //             return Response::deny();
    //     }
    // }

    // /**
    //  * Determine whether the user can delete details.
    //  *
    //  * @param  User  $user
    //  * @param  ItemModel  $item
    //  * @param  ActorModel  $actor
    //  * @return mixed
    //  */
    // public function destroy(User $user, ItemModel $item, ActorModel $actor)
    // {
    //     // TODO
    //     switch (get_class($actor)) {
    //         case RetailerModel::class:
    //             break;
    //         case ResellerModel::class:
    //             break;
    //         case ConsumerModel::class:
    //             break;
    //     }

    //     return Response::allow();
    // }
}
