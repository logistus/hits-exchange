<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTextAdsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('text_ads', function (Blueprint $table) {
      $table->id();
      $table->foreignId("user_id");
      $table->string('body', 50);
      $table->string('target_url');
      $table->string('text_color', 7);
      $table->string('bg_color', 7);
      $table->unsignedTinyInteger('text_bold')->default(0);
      $table->unsignedMediumInteger('views')->default(0);
      $table->unsignedMediumInteger('assigned')->default(0);
      $table->unsignedMediumInteger('clicks')->default(0);
      $table->enum('status', ['Pending', 'Active', 'Paused', 'Suspended']);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('text_ads');
  }
}
