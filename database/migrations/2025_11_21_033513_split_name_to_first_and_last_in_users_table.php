<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //first drop the name column
            $table->dropColumn('name');
            //add the first name column
            $table->string('first_name');
            //add the last name column
            $table->string('last_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //create the name column
            $table->string('name');
            //drop the first name column
            $table->dropColumn('first_name');
            //drop the last name column
            $table->dropColumn('last_name');
        });
    }
};
