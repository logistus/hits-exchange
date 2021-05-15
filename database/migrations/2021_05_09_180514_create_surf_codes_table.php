<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSurfCodesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('surf_codes', function (Blueprint $table) {
      $table->id();
      $table->string('code');
      $table->date('valid_from');
      $table->date('valid_to');
      $table->unsignedSmallInteger('surf_amount');
      $table->boolean('confirmed');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('surf_codes');
  }
}
