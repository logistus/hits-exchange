<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSignupBonusesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('signup_bonuses', function (Blueprint $table) {
      $table->id();
      $table->enum('bonus_type', ['Credits', 'Banner Impressions', 'Square Banner Impressions', 'Text Ad Impressions', 'Purchase Balance']);
      $table->unsignedMediumInteger('surf_amount');
      $table->unsignedMediumInteger('bonus_amount');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('signup_bonuses');
  }
}
