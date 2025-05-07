<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('activity_log', function (Blueprint $table) {
            $table->foreignId('project_management_id')->nullable()->constrained()->onDelete('cascade')->after('project_id');
        });
    }

    public function down()
    {
        Schema::table('activity_log', function (Blueprint $table) {
            $table->dropForeign(['project_management_id']);
            $table->dropColumn('project_management_id');
        });
    }
};
