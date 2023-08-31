<?php

namespace App\Policies\Actor;

use Illuminate\Auth\Access\HandlesAuthorization;

class Admin
{
    use HandlesAuthorization; // HandlesGenericUsers;
}
