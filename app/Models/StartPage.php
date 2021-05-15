<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StartPage extends Model
{
  use HasFactory;
  public $timestapmps = false;
  protected $guarded = [];

  public function active_start_pages()
  {
    return $this->status == 'Active';
  }
}
