<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSurfCodePrizesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('surf_code_prizes', function (Blueprint $table) {
      $table->id();
      $table->foreignId('code_id');
      $table->unsignedDecimal('prize_amount');
      $table->enum('prize_type', ['Credits', 'Banner Impressions', 'Square Banner Impressions', 'Text Ad Impressions', 'Purchase Balance']);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('surf_code_prizes');
  }
}
