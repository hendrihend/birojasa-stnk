@extends('layouts.app')

@section('title', 'Tambah User Baru')
@section('header_title', 'Buat Akun Pengguna')

@section('content')
    <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); max-width: 500px;">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Nama Lengkap</label>
                <input type="text" name="name" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Email</label>
                <input type="email" name="email" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Password</label>
                <input type="password" name="password" required minlength="8" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Hak Akses (Role)</label>
                <select name="role" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="admin">Admin Biasa (Operasional)</option>
                    <option value="superadmin">Super Admin (Akses Penuh)</option>
                </select>
            </div>

            <button type="submit" style="padding: 10px 20px; background: #111; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Simpan Akun</button>
            <a href="{{ route('users.index') }}" style="margin-left: 10px; color: #333; text-decoration: none;">Batal</a>
        </form>
    </div>
@endsection