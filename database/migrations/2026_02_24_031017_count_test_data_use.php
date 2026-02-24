<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('test_data', function (Blueprint $table) {
            $table->integer('latest_times_retrieved', false, true)->default(0);
            $table->integer('total_times_retrieved', false, true)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test_data', function (Blueprint $table) {
            $table->dropColumn('latest_times_retrieved');
            $table->dropColumn('total_times_retrieved');
        });
    }
};
