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
      $table->unsignedMediumInteger('minimum_page');
      $table->unsignedMediumInteger('prize_amount');
      $table->enum('prize_type', ['Credits', 'Banner Impressions', 'Text Ad Impressions', 'Purchase Balance']);
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
