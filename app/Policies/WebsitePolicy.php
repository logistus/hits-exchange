<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Website;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class WebsitePolicy
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
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Website  $website
     * @return mixed
     */
    public function view(User $user, Website $website)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Website  $website
     * @return mixed
     */
    public function update(User $user, Website $website)
    {
        return $user->id === $website->user_id
            ? Response::allow()
            : Response::deny('You do not own this website.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Website  $website
     * @return mixed
     */
    public function delete(User $user, Website $website)
    {
        return $user->id === $website->user_id
            ? Response::allow()
            : Response::deny('You do not own this website.');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Website  $website
     * @return mixed
     */
    public function restore(User $user, Website $website)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Website  $website
     * @return mixed
     */
    public function forceDelete(User $user, Website $website)
    {
        //
    }
}
