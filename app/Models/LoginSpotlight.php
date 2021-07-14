<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginSpotlight extends Model
{
  use HasFactory;
  public $timestamps = false;
  protected $guarded = [];

  public function active_login_spotlights()
  {
    return $this->status == 'Active';
  }
}
