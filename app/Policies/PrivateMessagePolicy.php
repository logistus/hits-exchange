<?php

namespace App\Policies;

use App\Models\PrivateMessage;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class PrivateMessagePolicy
{
  use HandlesAuthorization;

  public function view(User $user, PrivateMessage $privateMessage)
  {
    return ($user->id === $privateMessage->to_id && $privateMessage->deleted_from_receiver == 0) ||
      ($user->id === $privateMessage->from_id && $privateMessage->deleted_from_sender == 0) ?
      Response::allow()
      : Response::deny('You don\'t have permission to view this private message');
  }

  public function update(User $user, PrivateMessage $privateMessage)
  {
    return $user->id === $privateMessage->to_id ?
      Response::allow()
      : Response::deny('You don\'t have permission to do this action.');
  }

  public function delete_from_sender(User $user, PrivateMessage $privateMessage)
  {
    return $user->id === $privateMessage->from_id ?
      Response::allow()
      : Response::deny('You can\'t delete this private message.');
  }

  public function delete_from_receiver(User $user, PrivateMessage $privateMessage)
  {
    return $user->id === $privateMessage->to_id ?
      Response::allow()
      : Response::deny('You can\'t delete this private message.');
  }
}
