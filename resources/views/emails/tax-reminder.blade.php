<h3>Halo, Bpk/Ibu {{ $vehicle->client->nama_lengkap }}</h3>
<p>Ini adalah pesan pengingat otomatis dari sistem pengelolaan pajak kendaraan BJ STNK.</p>
<p>Kami informasikan bahwa kendaraan Anda dengan rincian berikut akan segera mendekati masa jatuh tempo:</p>
<ul>
    <li><strong>No. Polisi:</strong> {{ $vehicle->nopol }}</li>
    <li><strong>Merk/Tipe:</strong> {{ $vehicle->merk }} {{ $vehicle->tipe }}</li>
    <li><strong>Jatuh Tempo Pajak 1 Tahunan:</strong> {{ $vehicle->stnk->tgl_jatuh_tempo_pajak }}</li>
    <li><strong>Jatuh Tempo STNK 5 Tahunan:</strong> {{ $vehicle->stnk->tgl_habis_stnk }}</li>
</ul>
<p>Mohon segera menyiapkan dokumen terkait agar tidak terkena denda keterlambatan.</p>
<p>Terima kasih,</p>
<p><strong>Admin BJ STNK</strong></p>
