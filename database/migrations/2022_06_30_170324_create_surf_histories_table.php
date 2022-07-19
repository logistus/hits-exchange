<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSurfHistoriesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('surf_histories', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id');
      $table->date('surf_date');
      $table->unsignedInteger('surfed_total')->default(0);
      $table->unsignedDecimal('credits_total')->default(0);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('surf_histories');
  }
}
