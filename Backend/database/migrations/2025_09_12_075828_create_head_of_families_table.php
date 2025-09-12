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
        Schema::create('head_of_families', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->string('profile_picture')->nullable();
            $table->integer('inditity_number')->unique();
            $table->enum('gender', ['male', 'female']);
            $table->date('birth_date');
            $table->string('phone_number')->nullable();
            $table->string('occupation')->nullable();
            $table->enum('marital_status', ['single', 'married']);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('head_of_families');
    }
};
