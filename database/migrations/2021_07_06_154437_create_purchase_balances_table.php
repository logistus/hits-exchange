<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseBalancesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('purchase_balances', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id');
      $table->foreignId('order_id')->nullable();
      $table->enum('type', ['Commission Convert', 'Surf Prize', 'Signup Bonus', 'Purchase']);
      $table->decimal('amount');
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
    Schema::dropIfExists('purchase_balances');
  }
}
