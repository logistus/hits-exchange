<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
  use HandlesAuthorization;

  public function delete(User $user, Order $order)
  {
    return $user->id === $order->user_id
      ? Response::allow()
      : Response::deny('You can\'t delete this order.');
  }
}
