<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Menambahkan 3 kolom rincian biaya setelah kolom status_proses
            $table->bigInteger('biaya_pajak')->default(0)->after('status_proses');
            $table->bigInteger('biaya_jasa')->default(0)->after('biaya_pajak');
            $table->bigInteger('biaya_lain')->default(0)->after('biaya_jasa');
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['biaya_pajak', 'biaya_jasa', 'biaya_lain']);
        });
    }
};
