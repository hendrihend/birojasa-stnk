@extends('layouts.app')

@section('title', 'Dokumen Kendaraan')
@section('header_title', 'Arsip Dokumen: ' . $vehicle->nopol)

@section('content')
    <!-- Tombol Kembali -->
    <a href="{{ route('vehicles.index') }}" style="display: inline-block; margin-bottom: 20px; padding: 8px 15px; background: #eee; color: #333; text-decoration: none; border-radius: 4px; font-weight: bold;">&larr; Kembali ke Daftar Kendaraan</a>

    <!-- Menampilkan pesan sukses/error -->
    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border-radius: 4px;">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div style="background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 20px; border-radius: 4px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div style="display: flex; gap: 20px; flex-wrap: wrap;">
        
        <!-- BAGIAN KIRI: Form Upload -->
        <div style="flex: 1; min-width: 300px; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); align-self: flex-start;">
            <h3 style="margin-top: 0; border-bottom: 2px solid #eee; padding-bottom: 10px;">Unggah Dokumen Baru</h3>
            
            <form action="{{ route('documents.store', $vehicle->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Jenis Dokumen</label>
                    <select name="jenis_dokumen" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                        <option value="">-- Pilih Jenis Dokumen --</option>
                        <option value="KTP">KTP (Pemilik)</option>
                        <option value="STNK">STNK (Asli/Fotokopi)</option>
                        <option value="BPKB">BPKB</option>
                        <option value="Faktur">Faktur Kendaraan</option>
                        <option value="Kwitansi">Kwitansi Pembelian</option>
                        <option value="Surat Jalan">Surat Jalan / Pengantar</option>
                    </select>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Pilih File (JPG/PNG/PDF)</label>
                    <input type="file" name="file_dokumen" accept=".jpg,.jpeg,.png,.pdf" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; background: #f9f9f9;">
                    <small style="color: #666; display: block; margin-top: 5px;">* Maksimal ukuran file 2MB</small>
                </div>

                <button type="submit" style="width: 100%; padding: 10px; background: #111; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Upload File</button>
            </form>
        </div>

        <!-- BAGIAN KANAN: Tabel Daftar Dokumen -->
        <div style="flex: 2; min-width: 400px; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
            <h3 style="margin-top: 0; border-bottom: 2px solid #eee; padding-bottom: 10px;">Daftar Dokumen Tersimpan</h3>
            
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid #eee;">
                        <th style="padding: 12px 0;">Jenis Dokumen</th>
                        <th>Preview</th>
                        <th>Tanggal Upload</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $doc)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 12px 0; font-weight: bold;">{{ $doc->jenis_dokumen }}</td>
                        <td>
                            <!-- Link untuk melihat file. asset('storage/...') merujuk ke public/storage -->
                            <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" style="color: #3498db; text-decoration: none;">Lihat File &nearr;</a>
                        </td>
                        <td style="font-size: 13px; color: #666;">{{ $doc->created_at->format('d M Y H:i') }}</td>
                        <td>
                            <!-- Tombol Hapus -->
                            <form action="{{ route('documents.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus dokumen ini secara permanen?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="color: #e74c3c; background: none; border: none; cursor: pointer; font-size: 14px; text-decoration: underline;">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 30px; color: #999;">Belum ada dokumen yang diunggah untuk kendaraan ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection
