<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateUserReferralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_referrals', function ($table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->string('nonce', 64)->unique();
            $table->string('to_name');
            $table->string('to_email');
            $table->string('related_user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_referrals');
    }
}
