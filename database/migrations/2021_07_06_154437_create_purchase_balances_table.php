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
      $table->enum('type', ['Commission Transfer', 'Surf Prize', 'Signup Bonus', 'Purchase', 'Deposit']);
      $table->decimal('amount');
      $table->timestamp('created_at')->useCurrent();
      $table->enum('status', ['Completed', 'Pending'])->default('Pending');
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
