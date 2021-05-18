<?php

namespace App\Policies;

use App\Models\Balance;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BalancePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasCurrentTeamPermission('balance:read');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Balance  $balance
     * @return mixed
     */
    public function view(User $user, Balance $balance)
    {
        return $user->hasCurrentTeamPermission('balance:read');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasCurrentTeamPermission('balance:create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Balance  $balance
     * @return mixed
     */
    public function update(User $user, Balance $balance)
    {
        return $user->hasCurrentTeamPermission('balance:update');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Balance  $balance
     * @return mixed
     */
    public function delete(User $user, Balance $balance)
    {
        return $user->hasCurrentTeamPermission('balance:delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Balance  $balance
     * @return mixed
     */
    public function restore(User $user, Balance $balance)
    {
        return $user->hasCurrentTeamPermission('balance:restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Balance  $balance
     * @return mixed
     */
    public function forceDelete(User $user, Balance $balance)
    {
        return $user->hasCurrentTeamPermission('balance:forceDelete');
    }
}