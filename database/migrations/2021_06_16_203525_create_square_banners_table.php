<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSquareBannersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('square_banners', function (Blueprint $table) {
      $table->id();
      $table->foreignId("user_id");
      $table->string('image_url');
      $table->string('target_url');
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
    Schema::dropIfExists('square_banners');
  }
}
