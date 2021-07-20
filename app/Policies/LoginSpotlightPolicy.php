<?php

namespace App\Policies;

use App\Models\User;
use App\Models\LoginSpotlight;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class LoginSpotlightPolicy
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
   * @param  \App\Models\LoginSpotlight  $loginSpotlight
   * @return mixed
   */
  public function view(User $user, LoginSpotlight $loginSpotlight)
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
   * @param  \App\Models\LoginSpotlight  $loginSpotlight
   * @return mixed
   */
  public function update(User $user, LoginSpotlight $loginSpotlight)
  {
    return $user->id === $loginSpotlight->user_id
      ? Response::allow()
      : Response::deny('You do not own this login spotlight.');
  }

  /**
   * Determine whether the user can delete the model.
   *
   * @param  \App\Models\User  $user
   * @param  \App\Models\LoginSpotlight  $loginSpotlight
   * @return mixed
   */
  public function delete(User $user, LoginSpotlight $loginSpotlight)
  {
    return $user->id === $loginSpotlight->user_id
      ? Response::allow()
      : Response::deny('You do not own this login spotlight.');
  }

  /**
   * Determine whether the user can restore the model.
   *
   * @param  \App\Models\User  $user
   * @param  \App\Models\LoginSpotlight  $loginSpotlight
   * @return mixed
   */
  public function restore(User $user, LoginSpotlight $loginSpotlight)
  {
    //
  }

  /**
   * Determine whether the user can permanently delete the model.
   *
   * @param  \App\Models\User  $user
   * @param  \App\Models\LoginSpotlight  $loginSpotlight
   * @return mixed
   */
  public function forceDelete(User $user, LoginSpotlight $loginSpotlight)
  {
    //
  }
}
