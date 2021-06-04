<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('users', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('surname');
      $table->string('email')->unique();
      $table->string('username')->unique();
      $table->timestamp('email_verified_at')->nullable();
      $table->string('password');
      $table->foreignId('upline')->nullable();
      $table->string('country', 3)->nullable();
      $table->foreignId('user_type')->default(1);
      $table->unsignedDecimal('credits')->default(0);
      $table->unsignedMediumInteger('banner_imps')->default(0);
      $table->unsignedMediumInteger('text_imps')->default(0);
      $table->unsignedSmallInteger('surfed_today')->default(0);
      $table->unsignedBigInteger('start_time')->default(0);
      $table->unsignedBigInteger('last_click')->default(0);
      $table->unsignedInteger('correct_clicks')->default(0);
      $table->unsignedInteger('wrong_clicks')->default(0);
      $table->boolean('surfer_reward_claimed')->default(0);
      $table->boolean('login_spotlight_viewed')->default(0);
      $table->boolean('referral_notification')->default(1);
      $table->boolean('commission_notification')->default(1);
      $table->boolean('pm_notification')->default(1);
      $table->rememberToken();
      $table->timestamp('last_login')->nullable();
      $table->ipAddress('ip_address')->nullable();
      $table->timestamp('join_date');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('users');
  }
}
