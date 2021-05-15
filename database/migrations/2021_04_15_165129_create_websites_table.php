<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebsitesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('websites', function (Blueprint $table) {
      $table->id();
      $table->foreignId("user_id");
      $table->string("url");
      $table->enum('status', ['Pending', 'Active', 'Paused', 'Suspended']);
      $table->unsignedMediumInteger('views')->default(0);
      $table->unsignedMediumInteger('views_today')->default(0);
      $table->unsignedMediumInteger('max_daily_views')->default(0);
      $table->unsignedDecimal('assigned')->default(0);
      $table->unsignedTinyInteger('auto_assign')->default(0);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('websites');
  }
}
