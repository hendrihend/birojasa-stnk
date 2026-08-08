<?php

return [
    'required' => 'Kolom :attribute wajib diisi.',
    'string'   => 'Kolom :attribute harus berupa teks.',
    'numeric'  => 'Kolom :attribute harus berupa angka.',
    'unique'   => 'Data :attribute ini sudah terdaftar di sistem.',
    'exists'   => 'Data :attribute yang dipilih tidak valid.',
    'file'     => 'Kolom :attribute harus berupa file yang valid.',
    'mimes'    => 'Format :attribute harus berupa: :values.',
    'in'       => 'Pilihan :attribute tidak valid.',
    'max'      => [
        'numeric' => 'Kolom :attribute tidak boleh lebih dari :max.',
        'file'    => 'Ukuran file :attribute tidak boleh lebih dari :max kilobyte.',
        'string'  => 'Teks :attribute tidak boleh lebih dari :max karakter.',
    ],
    
    // Mengubah nama variabel bahasa Inggris menjadi Indonesia agar luwes dibaca
    'attributes' => [
        'nama_lengkap' => 'Nama Lengkap',
        'no_whatsapp' => 'Nomor WhatsApp',
        'nopol' => 'Nomor Polisi',
        'vehicle_id' => 'Kendaraan',
        'file_dokumen' => 'Dokumen File',
        'jenis_dokumen' => 'Jenis Dokumen',
        'total_biaya' => 'Total Biaya'
    ],
];