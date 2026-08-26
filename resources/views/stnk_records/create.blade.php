@extends ('layouts.app')

@section ('title', 'Tambah Data STNK & Pajak')
@section ('header_title', 'Tambah Data STNK / Pajak Baru')

@section ('content')
    <div
        style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); max-width: 600px;">
        @if ($errors->any())
            <div
                style="background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 20px; border-radius: 4px;">
                <ul style="margin: 0; padding-left: 20px">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('stnk_records.store') }}" method="POST">
            @csrf

            <div style="margin-bottom: 15px">
                <label style="display: block; font-weight: bold; margin-bottom: 5px"
                    >Pilih Kendaraan *</label>
                <select
                    name="vehicle_id"
                    required
                    style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px">
                    <option value="">-- Pilih Kendaraan --</option>
                    @foreach ($vehicles as $vehicle)
                        <option
                            value="{{ $vehicle->id }}"
                            {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                            {{ $vehicle->nopol }} - {{ $vehicle->client->nama_lengkap ?? 'Tanpa Pemilik' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 15px">
                <label style="display: block; font-weight: bold; margin-bottom: 5px"
                    >Nomor STNK *</label>
                <input type="text" name="no_stnk" value="{{ old('no_stnk') }}"required placeholder="Contoh: 12345678"
                    style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px"/>
            </div>

            <div style="display: flex; gap: 15px; margin-bottom: 15px">
                <div style="flex: 1">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px">Tgl Jatuh Tempo Pajak (Tahunan) *</label>
                    <input type="date" name="tgl_jatuh_tempo_pajak" value="{{ old('tgl_jatuh_tempo_pajak') }}" required
                        style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;"/>
                </div>
                <div style="flex: 1">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px">Tgl Habis STNK (5 Tahunan) *</label>
                    <input type="date" name="tgl_habis_stnk" value="{{ old('tgl_habis_stnk') }}" required style=" width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;"/>
                </div>
            </div>

            <div style="margin-bottom: 20px">
                <label style="display: block; font-weight: bold; margin-bottom: 5px">Status Aktif *</label>
                <select name="status_aktif" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px">
                    <option value="1" {{ old('status_aktif') == '1' ? 'selected' : '' }}>
                        Aktif (STNK Terbaru)
                    </option>
                    <option
                        value="0"
                        {{ (old('status_aktif') !== null && old('status_aktif') == '0') ? 'selected' : '' }}
                    >
                        Nonaktif (Hanya Riwayat/Arsip)
                    </option>
                </select>
                <small style="color: #666; display: block; margin-top: 5px"
                    >* Memilih "Aktif" akan menonaktifkan catatan STNK sebelumnya untuk kendaraan
                    ini (jika ada).</small
                >
            </div>

            <button
                type="submit"
                style="
                    padding: 10px 20px;
                    background: #111;
                    color: #fff;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                    font-weight: bold;
                "
            >
                Simpan Data
            </button>
            <a
                href="{{ route('stnk_records.index') }}"
                style="margin-left: 10px; color: #333; text-decoration: none"
                >Batal</a
            >
        </form>
    </div>
@endsection
