<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdPricesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('ad_prices', function (Blueprint $table) {
      $table->id();
      $table->enum('ad_type', ['Credits', 'Banner Impressions', 'Square Banner Impressions', 'Text Impressions', 'Start Page', 'Login Spotlight']);
      $table->unsignedMediumInteger('ad_amount');
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
    Schema::dropIfExists('ad_prices');
  }
}
