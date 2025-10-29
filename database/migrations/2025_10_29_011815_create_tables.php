<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid('osu_uuid')->unique()->invisible();
            $table->string('onid', 32);
            $table->timestamps();
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->string('name', 128);
            $table->char('api_key', 128)->unique();
            $table->timestamps();
        });

        Schema::create('project_users', function (Blueprint $table) {
            $table->id();
            $table->enum('role', ['owner', 'contributor', 'viewer']);
            $table->timestamps();
            
            $table->foreignId('project_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->unique(['project_id', 'user_id']);
        });

        Schema::create('data', function (Blueprint $table) {
            $table->id();
            $table->text('data');
            $table->timestamps();
            
            $table->foreignId('project_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations
     */
    public function down(): void
    {
        Schema::dropIfExists('data');
        Schema::dropIfExists('project_users');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('users');
    }
};
