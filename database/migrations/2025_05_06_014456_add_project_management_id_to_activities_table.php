<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('activities', function (Blueprint $table) {
            // Add the foreign key column
            $table->foreignId('project_management_id')
                ->nullable()
                ->constrained('project_management')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropForeign(['project_management_id']);
            $table->dropColumn('project_management_id');
        });
    }
};
