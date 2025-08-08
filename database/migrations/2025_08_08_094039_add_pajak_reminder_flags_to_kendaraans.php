<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    // database/migrations/xxxx_add_pajak_reminder_flags_to_kendaraans.php
    public function up()
    {
        Schema::table('kendaraans', function (Blueprint $table) {
            $table->boolean('reminder_h7_sent')->default(false);
            $table->boolean('reminder_h0_sent')->default(false);
        });
    }

    public function down()
    {
        Schema::table('kendaraans', function (Blueprint $table) {
            $table->dropColumn(['reminder_h7_sent', 'reminder_h0_sent']);
        });
    }

};
