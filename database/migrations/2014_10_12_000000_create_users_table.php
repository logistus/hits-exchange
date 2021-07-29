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
      $table->unsignedInteger('upgrade_expires')->nullable();
      $table->string('payment_type')->nullable();
      $table->string('btc_address')->unique()->nullable();
      $table->string('coinbase_email')->unique()->nullable();
      $table->unsignedDecimal('credits')->default(0);
      $table->unsignedMediumInteger('banner_imps')->default(0);
      $table->unsignedMediumInteger('square_banner_imps')->default(0);
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
      $table->date('last_login')->nullable();
      $table->ipAddress('ip_address')->nullable();
      $table->decimal('total_purchased')->default(0);
      $table->date('join_date')->default(date("Y-m-d"));
      $table->enum('status', ['Unverified', 'Active', 'Suspended'])->default('Unverified');
      $table->string('suspend_reason', 100);
      $table->date('suspend_until')->nullable();
      $table->boolean('admin')->default(0);
      $table->unsignedSmallInteger('claim_surf_prize');
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
