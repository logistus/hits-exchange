<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
  use HasFactory;
  public $timestamps = false;
  protected $guarded = [];

  public function users()
  {
    return $this->belongsTo(User::class, 'user_type');
  }

  public function prices()
  {
    return $this->hasMany(UpgradePrice::class, 'user_type_id')->where('time_type', '!=', 'Day');
  }
}
