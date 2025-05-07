<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('activity_log', function (Blueprint $table) {
            $table->id();
            $table->string('log_name')->nullable();
            $table->text('description');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('task_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('cascade');
            $table->json('properties')->nullable();
            $table->timestamps();

            // Add indexes for better performance
            $table->index('user_id');
            $table->index('task_id');
            $table->index('project_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('activity_log');
    }
};
