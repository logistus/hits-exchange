<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('orders', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id');
      $table->enum('order_type', ['Credit', 'Banner Impressions', 'Text Impressions', 'Square Banner Impressions', 'Start Page', 'Login Spotlight']);
      $table->string('order_item');
      $table->unsignedMediumInteger('price');
      $table->enum('status', ['Waiting Payment', 'Completed', 'Cancelled']);
      $table->timestamp('created_at')->useCurrent();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('orders');
  }
}
