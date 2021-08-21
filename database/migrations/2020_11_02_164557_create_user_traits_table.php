<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTraitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_traits', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');

            $table->string('ocean');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_traits');
    }
}
