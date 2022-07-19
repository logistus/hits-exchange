<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoTracker extends Model
{
  use HasFactory;
  public $timestamps = false;
  protected $guarded = [];

  public function referrals()
  {
    return $this->hasMany(User::class, 'tracker', 'tracker_name');
  }
}
