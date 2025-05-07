<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('calendar_events', function (Blueprint $table) {
            $table->string('color')->default('#e63946')->after('all_day');
        });
    }

    public function down()
    {
        Schema::table('calendar_events', function (Blueprint $table) {
            $table->dropColumn('color');
        });
    }
};
