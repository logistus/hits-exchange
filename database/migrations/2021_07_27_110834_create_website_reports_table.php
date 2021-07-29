<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebsiteReportsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('website_reports', function (Blueprint $table) {
      $table->id();
      $table->foreignId('website_id');
      $table->foreignId('user_id');
      $table->string('report_reason')->nullable();
      $table->timestamp('create_at')->useCurrent();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('website_reports');
  }
}
