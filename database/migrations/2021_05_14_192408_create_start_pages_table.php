<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStartPagesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('start_pages', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id');
      $table->foreignId('order_id');
      $table->string('dates');
      $table->string('url');
      $table->unsignedMediumInteger('total_views');
      $table->enum('status', ['Active', 'Pending Payment', 'Suspended', 'Expired']);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('start_pages');
  }
}
