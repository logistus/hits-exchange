<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSurferRewardsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('surfer_rewards', function (Blueprint $table) {
      $table->id();
      $table->unsignedMediumInteger('page');
      $table->unsignedDecimal('credit_prize')->nullable();
      $table->unsignedDecimal('banner_prize')->nullable();
      $table->unsignedDecimal('square_banner_prize')->nullable();
      $table->unsignedDecimal('text_ad_prize')->nullable();
      $table->unsignedDecimal('purchase_balance')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('surfer_rewards');
  }
}
