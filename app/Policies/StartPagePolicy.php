<?php

namespace App\Policies;

use App\Models\StartPage;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class StartPagePolicy
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
   * @param  \App\Models\StartPage  $startPage
   * @return mixed
   */
  public function view(User $user, StartPage $startPage)
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
   * @param  \App\Models\StartPage  $startPage
   * @return mixed
   */
  public function update(User $user, StartPage $startPage)
  {
    return $user->id === $startPage->user_id
      ? Response::allow()
      : Response::deny('You do not own this start page.');
  }

  /**
   * Determine whether the user can delete the model.
   *
   * @param  \App\Models\User  $user
   * @param  \App\Models\StartPage  $startPage
   * @return mixed
   */
  public function delete(User $user, StartPage $startPage)
  {
    return $user->id === $startPage->user_id
      ? Response::allow()
      : Response::deny('You do not own this start page.');
  }

  /**
   * Determine whether the user can restore the model.
   *
   * @param  \App\Models\User  $user
   * @param  \App\Models\StartPage  $startPage
   * @return mixed
   */
  public function restore(User $user, StartPage $startPage)
  {
    //
  }

  /**
   * Determine whether the user can permanently delete the model.
   *
   * @param  \App\Models\User  $user
   * @param  \App\Models\StartPage  $startPage
   * @return mixed
   */
  public function forceDelete(User $user, StartPage $startPage)
  {
    //
  }
}
