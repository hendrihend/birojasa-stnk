@extends('layouts.app')

@section('title', 'Scan QR Kendaraan')
@section('header_title', 'Pindai QR Code')

@section('content')
    <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); max-width: 600px; margin: 0 auto; text-align: center;">
        
        <p style="color: #666; margin-bottom: 20px;">Arahkan kamera ke QR Code kendaraan. Sistem akan otomatis membuka data kendaraan tersebut.</p>

        <!-- Kotak Kamera Scanner -->
        <div id="reader" style="width: 100%; max-width: 500px; margin: 0 auto;"></div>
        
    </div>

    <!-- Import Library Scanner Kamera -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // Fungsi ketika QR berhasil terbaca
            function onScanSuccess(decodedText, decodedResult) {
                // Matikan kamera
                html5QrcodeScanner.clear();
                // Arahkan halaman ke URL yang ada di dalam QR Code
                window.location.href = decodedText;
            }

            function onScanFailure(error) {
                // Abaikan error saat proses mencari QR
            }

            // Setting Kamera
            let html5QrcodeScanner = new Html5QrcodeScanner(
                "reader",
                { fps: 10, qrbox: {width: 250, height: 250} },
                /* verbose= */ false
            );
            
            // Jalankan Kamera
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        });
    </script>
@endsection
