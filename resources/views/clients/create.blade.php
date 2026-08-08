<!DOCTYPE html>
<html lang="id">
<head>
    <title>Tambah Klien</title>
</head>
<body style="font-family: sans-serif; padding: 20px;">
    <h2>Tambah Data Klien</h2>

    @if($errors->any())
        <div style="color: red; margin-bottom: 15px;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('clients.store') }}" method="POST">
        @csrf
        <div style="margin-bottom: 15px;">
            <label>Nama Lengkap (Wajib)</label><br>
            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required style="width: 300px; padding: 8px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label>NIK KTP</label><br>
            <input type="text" name="nik" value="{{ old('nik') }}" style="width: 300px; padding: 8px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label>Nomor WhatsApp</label><br>
            <input type="text" name="no_whatsapp" value="{{ old('no_whatsapp') }}" style="width: 300px; padding: 8px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label>Alamat Domisili</label><br>
            <textarea name="alamat" rows="3" style="width: 300px; padding: 8px;">{{ old('alamat') }}</textarea>
        </div>

        <button type="submit" style="padding: 10px 20px; background: #111; color: #fff; border: none; cursor: pointer;">Simpan Data</button>
        <a href="{{ route('clients.index') }}" style="margin-left: 10px; color: #333;">Batal</a>
    </form>
</body>
</html>