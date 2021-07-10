<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommissionsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('commissions', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id');
      $table->foreignId('order_id')->nullable();
      $table->decimal('amount');
      $table->enum('status', ['Paid', 'Transferred'])->nullable();
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
    Schema::dropIfExists('commissions');
  }
}
