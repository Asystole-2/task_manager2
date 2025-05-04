<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('project_management_user', function (Blueprint $table) {
            $table->foreignId('project_management_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->string('role')->default('member');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('project_management_user');
    }
};
