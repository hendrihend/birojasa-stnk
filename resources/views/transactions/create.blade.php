@extends ('layouts.app')
@section ('title', 'Buat Transaksi')
@section ('header_title', 'Buat Transaksi Pengurusan Baru')
@section ('content')
    <div class="mx-auto max-w-4xl">
        {{-- Error Validation --}}
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

        {{-- Form --}}
        <form
            action="{{ route('transactions.store') }}"
            method="POST"
            class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm"
        >
            @csrf

            {{-- Kendaraan --}}
            <div class="relative mb-5">
                <label for="vehicle_search" class="mb-2 block text-sm font-semibold text-gray-700">
                    Pilih Kendaraan <span class="text-red-500">*</span>
                </label>

                {{-- Input pencarian --}}
                <div class="relative">
                    <div
                        class="pointer-events-none absolute inset-y-0 left-0 z-10 flex items-center pl-3"
                    >
                        <svg
                            class="h-5 w-5 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 6.04 6.04a7.5 7.5 0 0 0 10.61 10.61Z"
                            />
                        </svg>
                    </div>

                    <input
                        type="text"
                        id="vehicle_search"
                        autocomplete="off"
                        placeholder="Cari nomor polisi atau nama pemilik..."
                        class="block w-full rounded-lg border border-gray-300 bg-white py-2.5 pl-10 pr-10 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    />

                    <button
                        type="button"
                        id="clear_vehicle"
                        class="absolute inset-y-0 right-0 hidden items-center pr-3 text-gray-400 hover:text-gray-600"
                    >
                        ✕
                    </button>
                </div>

                {{-- Hidden input untuk dikirim ke Laravel --}}
                <input
                    type="hidden"
                    name="vehicle_id"
                    id="vehicle_id"
                    value="{{ old('vehicle_id', request('vehicle_id')) }}"
                    required
                />

                {{-- Dropdown hasil pencarian --}}
                <div
                    id="vehicle_dropdown"
                    class="absolute z-50 mt-1 hidden max-h-72 w-full overflow-y-auto rounded-lg border border-gray-200 bg-white shadow-lg"
                >
                    @foreach ($vehicles as $veh)
                        <button
                            type="button"
                            class="vehicle-option block w-full border-b border-gray-100 px-4 py-3 text-left transition hover:bg-indigo-50"
                            data-id="{{ $veh->id }}"
                            data-search="{{ strtolower(
                        $veh->nopol . ' ' .
                        ($veh->client->nama_lengkap ?? '') . ' ' .
                        ($veh->merk ?? '') . ' ' .
                        ($veh->tipe ?? '')
                    ) }}"
                            data-nopol="{{ $veh->nopol }}"
                            data-client="{{ $veh->client->nama_lengkap ?? 'Tanpa Pemilik' }}"
                            data-vehicle="{{ trim(($veh->merk ?? '') . ' ' . ($veh->tipe ?? '')) }}"
                        >
                            <div class="font-semibold text-gray-800">{{ $veh->nopol }}</div>

                            <div class="text-sm text-gray-600">
                                {{ $veh->client->nama_lengkap ?? 'Tanpa Pemilik' }}
                            </div>

                            @if ($veh->merk || $veh->tipe)
                                <div class="mt-1 text-xs text-gray-400">
                                    {{ trim(($veh->merk ?? '') . ' ' . ($veh->tipe ?? '')) }}
                                    @if ($veh->tahun_pembuatan)
                                        • {{ $veh->tahun_pembuatan }}
                                    @endif
                                </div>
                            @endif
                        </button>

                    @endforeach

                    {{-- Jika tidak ditemukan --}}
                    <div
                        id="vehicle_not_found"
                        class="hidden px-4 py-6 text-center text-sm text-gray-500"
                    >
                        Kendaraan tidak ditemukan.
                    </div>
                </div>

                {{-- Kendaraan yang dipilih --}}
                <div
                    id="selected_vehicle"
                    class="mt-3 hidden rounded-lg border border-indigo-200 bg-indigo-50 p-3"
                >
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-medium text-indigo-500">Kendaraan Dipilih</p>

                            <p id="selected_nopol" class="mt-1 text-sm font-bold text-gray-800"></p>

                            <p id="selected_client" class="text-sm text-gray-600"></p>

                            <p id="selected_detail" class="mt-1 text-xs text-gray-500"></p>
                        </div>

                        <button
                            type="button"
                            id="remove_vehicle"
                            class="text-xs font-medium text-red-500 hover:text-red-700"
                        >
                            Ganti
                        </button>
                    </div>
                </div>
            </div>

            {{-- Jenis Layanan --}}
            <div class="mb-5">
                <label for="jenis_layanan" class="mb-2 block text-sm font-semibold text-gray-700">
                    Jenis Layanan Jasa <span class="text-red-500">*</span>
                </label>

                <select
                    id="jenis_layanan"
                    name="jenis_layanan"
                    required
                    class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                >
                    <option
                        value="Pajak Tahunan"
                        {{ old('jenis_layanan') == 'Pajak Tahunan' ? 'selected' : '' }}
                    >
                        Pajak Tahunan
                    </option>

                    <option
                        value="Pajak 5 Tahunan (Ganti Kaleng)"
                        {{ old('jenis_layanan') == 'Pajak 5 Tahunan (Ganti Kaleng)' ? 'selected' : '' }}
                    >
                        Pajak 5 Tahunan (Ganti Kaleng)
                    </option>

                    <option
                        value="Mutasi Masuk/Keluar"
                        {{ old('jenis_layanan') == 'Mutasi Masuk/Keluar' ? 'selected' : '' }}
                    >
                        Mutasi Masuk/Keluar
                    </option>

                    <option
                        value="Balik Nama"
                        {{ old('jenis_layanan') == 'Balik Nama' ? 'selected' : '' }}
                    >
                        Balik Nama
                    </option>
                </select>
            </div>

            {{-- Total Biaya --}}
            <div class="mb-5">
                <label for="total_biaya" class="mb-2 block text-sm font-semibold text-gray-700">
                    Total Biaya (Rp) <span class="text-red-500">*</span>
                </label>

                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-500">
                        Rp
                    </span>

                    <input
                        id="total_biaya"
                        type="number"
                        name="total_biaya"
                        value="{{ old('total_biaya', 0) }}"
                        required
                        min="0"
                        class="block w-full rounded-lg border border-gray-300 py-2.5 pl-10 pr-3 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    />
                </div>
            </div>

            {{-- Tanggal Masuk --}}
            <div class="mb-6">
                <label for="tgl_masuk" class="mb-2 block text-sm font-semibold text-gray-700">
                    Tanggal Masuk Berkas <span class="text-red-500">*</span>
                </label>

                <input
                    id="tgl_masuk"
                    type="date"
                    name="tgl_masuk"
                    value="{{ old('tgl_masuk', date('Y-m-d')) }}"
                    required
                    class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                />
            </div>

            {{-- Tombol --}}
            <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-5">
                <a
                    href="{{ route('transactions.index') }}"
                    class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-300"
                >
                    Simpan Transaksi
                </button>
            </div>
        </form>
    </div>

@endsection
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('vehicle_search');
        const hiddenInput = document.getElementById('vehicle_id');
        const dropdown = document.getElementById('vehicle_dropdown');
        const options = document.querySelectorAll('.vehicle-option');
        const notFound = document.getElementById('vehicle_not_found');

        const selectedVehicle = document.getElementById('selected_vehicle');
        const selectedNopol = document.getElementById('selected_nopol');
        const selectedClient = document.getElementById('selected_client');
        const selectedDetail = document.getElementById('selected_detail');

        const clearButton = document.getElementById('clear_vehicle');
        const removeButton = document.getElementById('remove_vehicle');

        // ==============================
        // Buka dropdown ketika input diklik
        // ==============================

        searchInput.addEventListener('focus', function () {
            if (!hiddenInput.value) {
                dropdown.classList.remove('hidden');
                filterVehicles();
            }
        });

        // ==============================
        // Pencarian kendaraan
        // ==============================

        searchInput.addEventListener('input', function () {
            hiddenInput.value = '';

            selectedVehicle.classList.add('hidden');

            dropdown.classList.remove('hidden');

            filterVehicles();
        });

        function filterVehicles() {
            const keyword = searchInput.value.toLowerCase().trim();

            let found = 0;

            options.forEach(function (option) {
                const searchData = option.dataset.search;

                if (searchData.includes(keyword)) {
                    option.classList.remove('hidden');

                    found++;
                } else {
                    option.classList.add('hidden');
                }
            });

            if (found === 0) {
                notFound.classList.remove('hidden');
            } else {
                notFound.classList.add('hidden');
            }
        }

        // ==============================
        // Pilih kendaraan
        // ==============================

        options.forEach(function (option) {
            option.addEventListener('click', function () {
                const id = this.dataset.id;
                const nopol = this.dataset.nopol;
                const client = this.dataset.client;
                const vehicle = this.dataset.vehicle;

                hiddenInput.value = id;

                searchInput.value = nopol;

                selectedNopol.textContent = nopol;
                selectedClient.textContent = client;

                if (vehicle) {
                    selectedDetail.textContent = vehicle;
                } else {
                    selectedDetail.textContent = '';
                }

                selectedVehicle.classList.remove('hidden');

                dropdown.classList.add('hidden');

                clearButton.classList.remove('hidden');
                clearButton.classList.add('flex');
            });
        });

        // ==============================
        // Tombol Ganti kendaraan
        // ==============================

        removeButton.addEventListener('click', function () {
            hiddenInput.value = '';

            searchInput.value = '';

            selectedVehicle.classList.add('hidden');

            clearButton.classList.add('hidden');
            clearButton.classList.remove('flex');

            searchInput.focus();

            dropdown.classList.remove('hidden');

            filterVehicles();
        });

        // ==============================
        // Tombol X
        // ==============================

        clearButton.addEventListener('click', function () {
            hiddenInput.value = '';

            searchInput.value = '';

            selectedVehicle.classList.add('hidden');

            clearButton.classList.add('hidden');
            clearButton.classList.remove('flex');

            searchInput.focus();

            dropdown.classList.remove('hidden');

            filterVehicles();
        });

        // ==============================
        // Klik di luar dropdown
        // ==============================

        document.addEventListener('click', function (event) {
            const container = searchInput.closest('.mb-5');

            if (!container.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // ==============================
        // Jika sudah ada vehicle_id
        // dari dashboard "Urus Sekarang"
        // ==============================

        if (hiddenInput.value) {
            const selectedOption = document.querySelector(
                `.vehicle-option[data-id="${hiddenInput.value}"]`
            );

            if (selectedOption) {
                searchInput.value = selectedOption.dataset.nopol;

                selectedNopol.textContent = selectedOption.dataset.nopol;

                selectedClient.textContent = selectedOption.dataset.client;

                selectedDetail.textContent = selectedOption.dataset.vehicle;

                selectedVehicle.classList.remove('hidden');

                clearButton.classList.remove('hidden');
                clearButton.classList.add('flex');
            }
        }
    });
</script>
