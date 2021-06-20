<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrivateMessagesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('private_messages', function (Blueprint $table) {
      $table->id();
      $table->foreignId('from_id');
      $table->foreignId('to_id');
      $table->string('subject', 50);
      $table->text('message');
      $table->enum('folder_receiver', ['Inbox', 'Trash']);
      $table->boolean('deleted_from_sender')->default(0);
      $table->boolean('deleted_from_receiver')->default(0);
      $table->boolean('read')->default(0);
      $table->boolean('reported')->default(0);
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
    Schema::dropIfExists('private_messages');
  }
}
