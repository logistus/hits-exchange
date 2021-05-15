<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTypesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('user_types', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->unsignedTinyInteger('surf_timer');
      $table->unsignedDecimal('surf_ratio');
      $table->unsignedTinyInteger('max_websites');
      $table->unsignedTinyInteger('max_banners');
      $table->unsignedTinyInteger('max_texts');
      $table->unsignedTinyInteger('min_auto_assign');
      $table->unsignedTinyInteger('credits_to_banner');
      $table->unsignedTinyInteger('credits_to_text');
      $table->unsignedTinyInteger('credit_reward_ratio');
      $table->unsignedTinyInteger('commission_ratio');
      $table->string('default_text_ad_color', 7);
      $table->string('default_text_ad_bg_color', 7);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('user_types');
  }
}
