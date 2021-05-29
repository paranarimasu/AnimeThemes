<?php

namespace App\Policies;

use App\Models\Theme;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ThemePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Theme $theme
     * @return mixed
     */
    public function view(User $user, Theme $theme)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasCurrentTeamPermission('theme:create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Theme $theme
     * @return mixed
     */
    public function update(User $user, Theme $theme)
    {
        return $user->hasCurrentTeamPermission('theme:update');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Theme $theme
     * @return mixed
     */
    public function delete(User $user, Theme $theme)
    {
        return $user->hasCurrentTeamPermission('theme:delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Theme $theme
     * @return mixed
     */
    public function restore(User $user, Theme $theme)
    {
        return $user->hasCurrentTeamPermission('theme:restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Theme $theme
     * @return mixed
     */
    public function forceDelete(User $user, Theme $theme)
    {
        return $user->hasCurrentTeamPermission('theme:forceDelete');
    }
}
