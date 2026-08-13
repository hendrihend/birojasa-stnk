@extends('layouts.app')

@section('title', 'Tambah Kendaraan')
@section('header_title', 'Tambah Data Kendaraan')

@section('content')

<div class="max-w-4xl mx-auto">

    <!-- Error Message -->
    @if($errors->any())
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-4">
            <div class="flex items-start gap-3">

                <svg
                    class="mt-0.5 h-5 w-5 shrink-0 text-red-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 8v4m0 4h.01M10.29 3.86l-7.82 14a2 2 0 001.74 3h15.58a2 2 0 001.74-3l-7.82-14a2 2 0 00-3.48 0z"
                    />
                </svg>

                <div>
                    <p class="text-sm font-semibold text-red-700">
                        Terdapat kesalahan:
                    </p>

                    <ul class="mt-1 list-disc pl-5 text-sm text-red-600">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

            </div>
        </div>
    @endif


    <!-- Form -->
    <form
        action="{{ route('vehicles.store') }}"
        method="POST"
        class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm"
    >

        @csrf


        <!-- ================= DATA KLIEN ================= -->

        <div class="mb-6">

            <h2 class="mb-4 text-sm font-semibold text-gray-800">
                Data Klien
            </h2>

            <div>

                <label
                    for="client_id"
                    class="mb-1.5 block text-sm font-medium text-gray-700"
                >
                    Nama Klien
                    <span class="text-red-500">*</span>
                </label>

                <select
                    id="client_id"
                    name="client_id"
                    required
                    class="
                        block w-full
                        rounded-lg
                        border border-gray-300
                        bg-white
                        px-3 py-2.5
                        text-sm text-gray-700
                        shadow-sm
                        outline-none
                        transition
                        focus:border-indigo-500
                        focus:ring-2
                        focus:ring-indigo-500/20
                    "
                >

                    <option value="">
                        -- Pilih Klien --
                    </option>

                    @foreach($clients as $client)

                        <option
                            value="{{ $client->id }}"
                            {{ old('client_id') == $client->id ? 'selected' : '' }}
                        >
                            {{ $client->nama_lengkap }}
                            (NIK: {{ $client->nik ?? '-' }})
                        </option>

                    @endforeach

                </select>

            </div>

        </div>


        <!-- ================= IDENTITAS KENDARAAN ================= -->

        <div class="mb-6">

            <h2 class="mb-4 text-sm font-semibold text-gray-800">
                Identitas Kendaraan
            </h2>


            <!-- Nomor Polisi -->

            <div class="mb-4">

                <label
                    for="nopol"
                    class="mb-1.5 block text-sm font-medium text-gray-700"
                >
                    Nomor Polisi (Nopol)
                    <span class="text-red-500">*</span>
                </label>

                <input
                    id="nopol"
                    type="text"
                    name="nopol"
                    value="{{ old('nopol') }}"
                    required
                    placeholder="Contoh: B 1234 ABC"
                    class="
                        block w-full
                        rounded-lg
                        border border-gray-300
                        px-3 py-2.5
                        text-sm
                        uppercase
                        shadow-sm
                        outline-none
                        transition
                        placeholder:text-gray-400
                        focus:border-indigo-500
                        focus:ring-2
                        focus:ring-indigo-500/20
                    "
                >

            </div>


            <!-- No Rangka & No Mesin -->

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                <div>

                    <label
                        for="no_rangka"
                        class="mb-1.5 block text-sm font-medium text-gray-700"
                    >
                        No Rangka
                    </label>

                    <input
                        id="no_rangka"
                        type="text"
                        name="no_rangka"
                        value="{{ old('no_rangka') }}"
                        placeholder="Masukkan nomor rangka"
                        class="
                            block w-full
                            rounded-lg
                            border border-gray-300
                            px-3 py-2.5
                            text-sm
                            shadow-sm
                            outline-none
                            transition
                            placeholder:text-gray-400
                            focus:border-indigo-500
                            focus:ring-2
                            focus:ring-indigo-500/20
                        "
                    >

                </div>


                <div>

                    <label
                        for="no_mesin"
                        class="mb-1.5 block text-sm font-medium text-gray-700"
                    >
                        No Mesin
                    </label>

                    <input
                        id="no_mesin"
                        type="text"
                        name="no_mesin"
                        value="{{ old('no_mesin') }}"
                        placeholder="Masukkan nomor mesin"
                        class="
                            block w-full
                            rounded-lg
                            border border-gray-300
                            px-3 py-2.5
                            text-sm
                            shadow-sm
                            outline-none
                            transition
                            placeholder:text-gray-400
                            focus:border-indigo-500
                            focus:ring-2
                            focus:ring-indigo-500/20
                        "
                    >

                </div>

            </div>

        </div>


        <!-- ================= DETAIL KENDARAAN ================= -->

        <div class="mb-6">

            <h2 class="mb-4 text-sm font-semibold text-gray-800">
                Detail Kendaraan
            </h2>


            <!-- Merk & Tipe -->

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 mb-4">

                <div>

                    <label
                        for="merk"
                        class="mb-1.5 block text-sm font-medium text-gray-700"
                    >
                        Merk
                    </label>

                    <input
                        id="merk"
                        type="text"
                        name="merk"
                        value="{{ old('merk') }}"
                        placeholder="Contoh: Honda"
                        class="
                            block w-full
                            rounded-lg
                            border border-gray-300
                            px-3 py-2.5
                            text-sm
                            shadow-sm
                            outline-none
                            transition
                            placeholder:text-gray-400
                            focus:border-indigo-500
                            focus:ring-2
                            focus:ring-indigo-500/20
                        "
                    >

                </div>


                <div>

                    <label
                        for="tipe"
                        class="mb-1.5 block text-sm font-medium text-gray-700"
                    >
                        Tipe
                    </label>

                    <input
                        id="tipe"
                        type="text"
                        name="tipe"
                        value="{{ old('tipe') }}"
                        placeholder="Contoh: Vario 150"
                        class="
                            block w-full
                            rounded-lg
                            border border-gray-300
                            px-3 py-2.5
                            text-sm
                            shadow-sm
                            outline-none
                            transition
                            placeholder:text-gray-400
                            focus:border-indigo-500
                            focus:ring-2
                            focus:ring-indigo-500/20
                        "
                    >

                </div>

            </div>


            <!-- Tahun & Warna -->

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                <div>

                    <label
                        for="tahun_pembuatan"
                        class="mb-1.5 block text-sm font-medium text-gray-700"
                    >
                        Tahun Pembuatan
                    </label>

                    <input
                        id="tahun_pembuatan"
                        type="text"
                        name="tahun_pembuatan"
                        value="{{ old('tahun_pembuatan') }}"
                        placeholder="Contoh: 2020"
                        class="
                            block w-full
                            rounded-lg
                            border border-gray-300
                            px-3 py-2.5
                            text-sm
                            shadow-sm
                            outline-none
                            transition
                            placeholder:text-gray-400
                            focus:border-indigo-500
                            focus:ring-2
                            focus:ring-indigo-500/20
                        "
                    >

                </div>


                <div>

                    <label
                        for="warna"
                        class="mb-1.5 block text-sm font-medium text-gray-700"
                    >
                        Warna
                    </label>

                    <input
                        id="warna"
                        type="text"
                        name="warna"
                        value="{{ old('warna') }}"
                        placeholder="Contoh: Merah"
                        class="
                            block w-full
                            rounded-lg
                            border border-gray-300
                            px-3 py-2.5
                            text-sm
                            shadow-sm
                            outline-none
                            transition
                            placeholder:text-gray-400
                            focus:border-indigo-500
                            focus:ring-2
                            focus:ring-indigo-500/20
                        "
                    >

                </div>

            </div>

        </div>


        <!-- ================= PEMILIK ================= -->

        <div class="mb-6">

            <h2 class="mb-4 text-sm font-semibold text-gray-800">
                Data Pemilik
            </h2>

            <div>

                <label
                    for="nama_pemilik"
                    class="mb-1.5 block text-sm font-medium text-gray-700"
                >
                    Nama Tercetak di STNK
                </label>

                <input
                    id="nama_pemilik"
                    type="text"
                    name="nama_pemilik"
                    value="{{ old('nama_pemilik') }}"
                    placeholder="Biarkan kosong jika sama dengan nama Klien"
                    class="
                        block w-full
                        rounded-lg
                        border border-gray-300
                        px-3 py-2.5
                        text-sm
                        shadow-sm
                        outline-none
                        transition
                        placeholder:text-gray-400
                        focus:border-indigo-500
                        focus:ring-2
                        focus:ring-indigo-500/20
                    "
                >

                <p class="mt-1.5 text-xs text-gray-500">
                    Isi jika nama yang tercetak pada STNK berbeda dengan nama klien.
                </p>

            </div>

        </div>


        <!-- ================= BUTTON ================= -->

        <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-5">

            <a
                href="{{ route('vehicles.index') }}"
                class="
                    rounded-lg
                    border border-gray-300
                    bg-white
                    px-4 py-2.5
                    text-sm
                    font-medium
                    text-gray-700
                    transition
                    hover:bg-gray-50
                "
            >
                Batal
            </a>


            <button
                type="submit"
                class="
                    inline-flex
                    items-center
                    gap-2
                    rounded-lg
                    bg-[#5e5acf]
                    px-4 py-2.5
                    text-sm
                    font-semibold
                    text-white
                    shadow-sm
                    transition
                    hover:bg-[#211f5c]
                    focus:outline-none
                    focus:ring-2
                    focus:ring-indigo-500
                    focus:ring-offset-2
                "
            >

                <svg
                    class="h-4 w-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5 13l4 4L19 7"
                    />
                </svg>

                Simpan Kendaraan

            </button>

        </div>

    </form>

</div>

@endsection