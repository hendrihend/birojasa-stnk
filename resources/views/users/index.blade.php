@extends('layouts.app')

@section('title', 'Manajemen User')
@section('header_title', 'Kelola Pengguna Sistem')

@section('content')
    <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
        
        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 4px;">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div style="background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 4px;">{{ $errors->first() }}</div>
        @endif

        <a href="{{ route('users.create') }}" style="display: inline-block; margin-bottom: 20px; padding: 10px 15px; background: #111; color: #fff; text-decoration: none; border-radius: 4px;">+ Tambah User Baru</a>

        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid #eee;">
                    <th style="padding: 12px 0;">Nama</th>
                    <th>Email</th>
                    <th>Hak Akses (Role)</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 12px 0; font-weight: bold;">{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; background: {{ $user->role === 'superadmin' ? '#e8f4f8' : '#f9f9f9' }}; color: {{ $user->role === 'superadmin' ? '#2980b9' : '#555' }}; font-weight: bold;">
                            {{ strtoupper($user->role) }}
                        </span>
                    </td>
                    <td>
                        @if(auth()->id() != $user->id)
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" style="color: #e74c3c; background: none; border: none; cursor: pointer;">Hapus</button>
                            </form>
                        @else
                            <span style="color: #999; font-size: 12px;">Akun Anda</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
