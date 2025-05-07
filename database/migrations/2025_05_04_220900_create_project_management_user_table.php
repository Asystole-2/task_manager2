<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('project_management_user', function (Blueprint $table) {
            $table->foreignId('project_management_id')
                ->constrained('project_management')
                ->onDelete('cascade');

            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            $table->string('role')->default('member');

            // Composite primary key
            $table->primary(['project_management_id', 'user_id']);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('project_management_user');
    }
};
