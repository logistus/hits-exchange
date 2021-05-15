<?php

namespace App\Policies;

use App\Models\TextAd;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class TextAdPolicy
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
   * @param  \App\Models\TextAd  $textAd
   * @return mixed
   */
  public function view(User $user, TextAd $textAd)
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
   * @param  \App\Models\TextAd  $textAd
   * @return mixed
   */
  public function update(User $user, TextAd $textAd)
  {
    return $user->id === $textAd->user_id
      ? Response::allow()
      : Response::deny('You do not own this text ad.');
  }

  /**
   * Determine whether the user can delete the model.
   *
   * @param  \App\Models\User  $user
   * @param  \App\Models\TextAd  $textAd
   * @return mixed
   */
  public function delete(User $user, TextAd $textAd)
  {
    return $user->id === $textAd->user_id
      ? Response::allow()
      : Response::deny('You do not own this text ad.');
  }

  /**
   * Determine whether the user can restore the model.
   *
   * @param  \App\Models\User  $user
   * @param  \App\Models\TextAd  $textAd
   * @return mixed
   */
  public function restore(User $user, TextAd $textAd)
  {
    //
  }

  /**
   * Determine whether the user can permanently delete the model.
   *
   * @param  \App\Models\User  $user
   * @param  \App\Models\TextAd  $textAd
   * @return mixed
   */
  public function forceDelete(User $user, TextAd $textAd)
  {
    //
  }
}
