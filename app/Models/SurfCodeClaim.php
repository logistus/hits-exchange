<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurfCodeClaim extends Model
{
  use HasFactory;
  public $timestamps = false;
  protected $guarded = [];

  public function code_info()
  {
    return $this->belongsTo(SurfCode::class, 'code_id');
  }
}
