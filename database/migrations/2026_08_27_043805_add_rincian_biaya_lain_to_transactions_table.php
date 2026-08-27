<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Menambahkan 8 detail biaya lain setelah kolom biaya_jasa
            $table->bigInteger('loket_pendaftaran')->default(0)->after('biaya_jasa');
            $table->bigInteger('loket_cek_fisik')->default(0)->after('loket_pendaftaran');
            $table->bigInteger('acc_tidak_hadir')->default(0)->after('loket_cek_fisik');
            $table->bigInteger('acc_domisili')->default(0)->after('acc_tidak_hadir');
            $table->bigInteger('loket_penetapan')->default(0)->after('acc_domisili');
            $table->bigInteger('loket_pengesahan_1')->default(0)->after('loket_penetapan');
            $table->bigInteger('loket_pengesahan_2')->default(0)->after('loket_pengesahan_1');
            $table->bigInteger('bea_materai')->default(0)->after('loket_pengesahan_2');
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'loket_pendaftaran', 'loket_cek_fisik', 'acc_tidak_hadir', 'acc_domisili', 
                'loket_penetapan', 'loket_pengesahan_1', 'loket_pengesahan_2', 'bea_materai'
            ]);
        });
    }
};
