<?php

namespace App\Policies;

use App\Models\Entry;
use App\Models\User;
use App\Models\Video;
use Illuminate\Auth\Access\HandlesAuthorization;

class EntryPolicy
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
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Entry  $entry
     * @return mixed
     */
    public function view(User $user, Entry $entry)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasCurrentTeamPermission('entry:create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Entry  $entry
     * @return mixed
     */
    public function update(User $user, Entry $entry)
    {
        return $user->hasCurrentTeamPermission('entry:update');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Entry  $entry
     * @return mixed
     */
    public function delete(User $user, Entry $entry)
    {
        return $user->hasCurrentTeamPermission('entry:delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Entry  $entry
     * @return mixed
     */
    public function restore(User $user, Entry $entry)
    {
        return $user->hasCurrentTeamPermission('entry:restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Entry  $entry
     * @return mixed
     */
    public function forceDelete(User $user, Entry $entry)
    {
        return $user->hasCurrentTeamPermission('entry:forceDelete');
    }

    /**
     * Determine whether the user can attach any video to the entry.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Entry  $entry
     * @return mixed
     */
    public function attachAnyVideo(User $user, Entry $entry)
    {
        return $user->hasCurrentTeamPermission('entry:update');
    }

    /**
     * Determine whether the user can attach a video to the entry.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Entry  $entry
     * @param  \App\Models\Video  $video
     * @return mixed
     */
    public function attachVideo(User $user, Entry $entry, Video $video)
    {
        if ($entry->videos->contains($video)) {
            return false;
        }

        return $user->hasCurrentTeamPermission('entry:update');
    }

    /**
     * Determine whether the user can detach a video from the entry.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Entry  $entry
     * @param  \App\Models\Video  $video
     * @return mixed
     */
    public function detachVideo(User $user, Entry $entry, Video $video)
    {
        return $user->hasCurrentTeamPermission('entry:update');
    }
}
