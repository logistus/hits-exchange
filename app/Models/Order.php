<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  use HasFactory;
  public $timestamps = false;
  protected $guarded = [];

  public function user()
  {
    $this->belongsTo(User::class, 'id', 'user_id');
  }
}
