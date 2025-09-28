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
        Schema::table('head_of_families', function (Blueprint $table) {
            if (Schema::hasColumn('head_of_families', 'inditity_number')) {
                $table->renameColumn('inditity_number', 'identity_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('head_of_families', function (Blueprint $table) {
            if (Schema::hasColumn('head_of_families', 'identity_number')) {
                $table->renameColumn('identity_number', 'inditity_number');
            }
        });
    }
};
