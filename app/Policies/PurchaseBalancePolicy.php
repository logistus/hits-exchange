<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PurchaseBalance;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class PurchaseBalancePolicy
{
  use HandlesAuthorization;

  public function delete(User $user, PurchaseBalance $pb)
  {
    return $user->id === $pb->user_id
      ? Response::allow()
      : Response::deny('You can\'t delete this purchase balance.');
  }
}
