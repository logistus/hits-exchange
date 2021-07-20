<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpgradePricesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('upgrade_prices', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_type_id');
      $table->enum('time_type', ['Day', 'Week', 'Month', 'Year']);
      $table->unsignedTinyInteger('time_amount');
      $table->decimal('price');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('upgrade_prices');
  }
}
