<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('data', 'upload_data');

        Schema::create('test_data', function (Blueprint $table) {
            $table->id();
            $table->text('data');
            $table->timestamps();

            $table->foreignId('project_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('upload_data', 'data');

        Schema::dropIfExists('test_data');
    }
};
