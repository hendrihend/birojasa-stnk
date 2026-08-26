@extends ('layouts.app')

@section ('title', 'Edit Kendaraan')
@section ('header_title', 'Edit Data Kendaraan')

@section ('content')
    <div class="mx-auto max-w-4xl">
        {{-- Pesan Error --}}
        @if ($errors->any())
            <div
                class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"
            >
                <p class="mb-2 font-semibold">Terdapat kesalahan:</p>

                <ul class="list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Edit --}}
        <form
            action="{{ route('vehicles.update', $vehicle->id) }}"
            method="POST"
            class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm"
        >
            @csrf
            @method ('PUT')

            {{-- Nama Klien --}}
            <div class="mb-5">
                <label for="client_id" class="mb-2 block text-sm font-semibold text-gray-700">
                    Nama Klien <span class="text-red-500">*</span>
                </label>

                <select
                    id="client_id"
                    name="client_id"
                    required
                    class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                >
                    <option value="">-- Pilih Klien --</option>

                    @foreach ($clients as $client)
                        <option
                            value="{{ $client->id }}"
                            {{ old('client_id', $vehicle->client_id) == $client->id ? 'selected' : '' }}
                        >
                            {{ $client->nama_lengkap }} (NIK: {{ $client->nik ?? '-' }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Nomor Polisi --}}
            <div class="mb-5">
                <label for="nopol" class="mb-2 block text-sm font-semibold text-gray-700">
                    Nomor Polisi (Nopol) <span class="text-red-500">*</span>
                </label>

                <input
                    id="nopol"
                    type="text"
                    name="nopol"
                    value="{{ old('nopol', $vehicle->nopol) }}"
                    required
                    class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm uppercase text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                />
            </div>

            {{-- No Rangka & No Mesin --}}
            <div class="mb-5 grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label for="no_rangka" class="mb-2 block text-sm font-semibold text-gray-700">
                        No Rangka
                    </label>

                    <input
                        id="no_rangka"
                        type="text"
                        name="no_rangka"
                        value="{{ old('no_rangka', $vehicle->no_rangka) }}"
                        placeholder="Masukkan nomor rangka"
                        class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    />
                </div>

                <div>
                    <label for="no_mesin" class="mb-2 block text-sm font-semibold text-gray-700">
                        No Mesin
                    </label>

                    <input
                        id="no_mesin"
                        type="text"
                        name="no_mesin"
                        value="{{ old('no_mesin', $vehicle->no_mesin) }}"
                        placeholder="Masukkan nomor mesin"
                        class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    />
                </div>
            </div>

            {{-- Merk & Tipe --}}
            <div class="mb-5 grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label for="merk" class="mb-2 block text-sm font-semibold text-gray-700">
                        Merk
                    </label>

                    <input
                        id="merk"
                        type="text"
                        name="merk"
                        value="{{ old('merk', $vehicle->merk) }}"
                        placeholder="Contoh: Honda"
                        class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    />
                </div>

                <div>
                    <label for="tipe" class="mb-2 block text-sm font-semibold text-gray-700">
                        Tipe
                    </label>

                    <input
                        id="tipe"
                        type="text"
                        name="tipe"
                        value="{{ old('tipe', $vehicle->tipe) }}"
                        placeholder="Contoh: Vario 150"
                        class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    />
                </div>
            </div>

            {{-- Tahun & Warna --}}
            <div class="mb-5 grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label
                        for="tahun_pembuatan"
                        class="mb-2 block text-sm font-semibold text-gray-700"
                    >
                        Tahun Pembuatan
                    </label>

                    <input
                        id="tahun_pembuatan"
                        type="text"
                        name="tahun_pembuatan"
                        value="{{ old('tahun_pembuatan', $vehicle->tahun_pembuatan) }}"
                        placeholder="Contoh: 2020"
                        class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    />
                </div>

                <div>
                    <label for="warna" class="mb-2 block text-sm font-semibold text-gray-700">
                        Warna
                    </label>

                    <input
                        id="warna"
                        type="text"
                        name="warna"
                        value="{{ old('warna', $vehicle->warna) }}"
                        placeholder="Contoh: Merah"
                        class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    />
                </div>
            </div>

            {{-- Nama Pemilik --}}
            <div class="mb-6">
                <label for="nama_pemilik" class="mb-2 block text-sm font-semibold text-gray-700">
                    Nama Tercetak di STNK
                </label>

                <input
                    id="nama_pemilik"
                    type="text"
                    name="nama_pemilik"
                    value="{{ old('nama_pemilik', $vehicle->nama_pemilik) }}"
                    placeholder="Biarkan kosong jika sama dengan nama Klien"
                    class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                />

                <p class="mt-1.5 text-xs text-gray-500">Isi jika nama yang tercetak di STNK berbeda dengan nama klien.</p>
            </div>

            {{-- Tombol --}}
            <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-5">
                <a
                    href="{{ route('vehicles.index') }}"
                    class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-300"
                >
                    Update Kendaraan
                </button>
            </div>
        </form>
    </div>

@endsection
