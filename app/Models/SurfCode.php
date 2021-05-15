<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurfCode extends Model
{
  use HasFactory;
  public $timestamps = false;
  protected $guarded = [];

  public function prizes()
  {
    return $this->hasMany(SurfCodePrize::class, 'code_id');
  }
}
