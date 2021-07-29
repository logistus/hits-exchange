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
      $table->enum('order_type', ['Credits', 'Banner Impressions', 'Square Banner Impressions', 'Text Impressions', 'Login Spotlight', 'Start Page', 'Upgrade', 'Credit Boost']);
      $table->string('order_item');
      $table->unsignedSmallInteger('order_amount');
      $table->foreignId('order_member_type')->nullable();
      $table->unsignedMediumInteger('price');
      $table->enum('status', ['Pending Payment', 'Completed'])->default('Pending Payment');
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
