<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumnsToUserTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('profile_nagged')->default(false);
        });

        Schema::table('user_profiles', function (Blueprint $table) {
            $table->boolean('avatar')->default(false);
            $table->date('dob')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('profile_nagged');
        });

        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn('avatar');
            $table->dropColumn('dob');
        });
    }
}
