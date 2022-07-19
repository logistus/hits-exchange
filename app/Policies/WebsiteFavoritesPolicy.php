<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WebsiteFavorites;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class WebsiteFavoritesPolicy
{
  use HandlesAuthorization;

  /**
   * Determine whether the user can view any models.
   *
   * @param  \App\Models\User  $user
   * @return \Illuminate\Auth\Access\Response|bool
   */
  public function viewAny(User $user)
  {
    //
  }

  /**
   * Determine whether the user can view the model.
   *
   * @param  \App\Models\User  $user
   * @param  \App\Models\WebsiteFavorites  $websiteFavorites
   * @return \Illuminate\Auth\Access\Response|bool
   */
  public function view(User $user, WebsiteFavorites $websiteFavorites)
  {
    //
  }

  /**
   * Determine whether the user can create models.
   *
   * @param  \App\Models\User  $user
   * @return \Illuminate\Auth\Access\Response|bool
   */
  public function create(User $user)
  {
    //
  }

  /**
   * Determine whether the user can update the model.
   *
   * @param  \App\Models\User  $user
   * @param  \App\Models\WebsiteFavorites  $websiteFavorites
   * @return \Illuminate\Auth\Access\Response|bool
   */
  public function update(User $user, WebsiteFavorites $websiteFavorites)
  {
    return $user->id === $websiteFavorites->user_id
      ? Response::allow()
      : Response::deny('You do not own this favorited ad.');
  }

  /**
   * Determine whether the user can delete the model.
   *
   * @param  \App\Models\User  $user
   * @param  \App\Models\WebsiteFavorites  $websiteFavorites
   * @return \Illuminate\Auth\Access\Response|bool
   */
  public function delete(User $user, WebsiteFavorites $websiteFavorites)
  {
    return $user->id === $websiteFavorites->user_id
      ? Response::allow()
      : Response::deny('You do not own this favorited ad.');
  }

  /**
   * Determine whether the user can restore the model.
   *
   * @param  \App\Models\User  $user
   * @param  \App\Models\WebsiteFavorites  $websiteFavorites
   * @return \Illuminate\Auth\Access\Response|bool
   */
  public function restore(User $user, WebsiteFavorites $websiteFavorites)
  {
    //
  }

  /**
   * Determine whether the user can permanently delete the model.
   *
   * @param  \App\Models\User  $user
   * @param  \App\Models\WebsiteFavorites  $websiteFavorites
   * @return \Illuminate\Auth\Access\Response|bool
   */
  public function forceDelete(User $user, WebsiteFavorites $websiteFavorites)
  {
    //
  }
}
