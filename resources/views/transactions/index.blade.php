@extends ('layouts.app')

@section ('title', 'Status Pengurusan dan Transaksi')
@section ('header_title', 'Manajemen Transaksi dan Status Pengurusan')

@section ('content')
    <div class="w-full">
        {{-- Pesan Sukses --}}
        @if (session('success'))
            <div
                class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700"
            >
                {{ session('success') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Daftar Transaksi</h2>

                <p class="mt-1 text-sm text-gray-500">Kelola transaksi dan status pengurusan kendaraan.</p>
            </div>

            <a
                href="{{ route('transactions.create') }}"
                class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-300"
            >
                + Buat Transaksi Baru
            </a>
        </div>

        {{-- FILTER --}}
        <div class="mb-5 rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="mb-4 flex items-center justify-between">
                <button
                    type="button"
                    id="resetFilter"
                    class="text-sm font-medium text-gray-500 hover:text-indigo-600"
                ></button>
            </div>

            {{-- Grid Filter --}}
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                {{-- Nomor Invoice --}}
                <div>
                    <label for="filterInvoice" class="mb-2 block text-sm font-medium text-gray-700">
                        Nomor Invoice
                    </label>

                    <input
                        type="text"
                        id="filterInvoice"
                        placeholder="Cari nomor invoice..."
                        class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    />
                </div>

                {{-- Jenis Layanan --}}
                <div>
                    <label for="filterLayanan" class="mb-2 block text-sm font-medium text-gray-700">
                        Jenis Layanan
                    </label>

                    <select
                        id="filterLayanan"
                        class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    >
                        <option value="">Semua Layanan</option>

                        @foreach ($transactions->pluck('jenis_layanan')->unique() as $layanan)
                            <option value="{{ strtolower($layanan) }}">{{ $layanan }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Status Proses --}}
                <div>
                    <label for="filterStatus" class="mb-2 block text-sm font-medium text-gray-700">
                        Status Proses
                    </label>

                    <select
                        id="filterStatus"
                        class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    >
                        <option value="">Semua Status</option>

                        @foreach ($transactions->pluck('status_proses')->unique() as $status)
                            <option value="{{ strtolower($status) }}">{{ $status }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Tanggal --}}
                <div>
                    <label for="filterTanggal" class="mb-2 block text-sm font-medium text-gray-700">
                        Tanggal Masuk
                    </label>

                    <input
                        type="date"
                        id="filterTanggal"
                        class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    />
                </div>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                        <tr class="border-b border-gray-200">
                            <th class="px-5 py-4 font-semibold">No Invoice</th>

                            <th class="px-5 py-4 font-semibold">Kendaraan & Klien</th>

                            <th class="px-5 py-4 font-semibold">Layanan</th>

                            <th class="px-5 py-4 font-semibold">Total Biaya</th>

                            <th class="px-5 py-4 font-semibold">Status Proses</th>

                            <th class="px-5 py-4 font-semibold">Tgl Masuk</th>

                            <th class="px-5 py-4 text-center font-semibold">Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="transactionTable">
                        @forelse ($transactions as $trx)
                            <tr
                                class="transaction-row border-b border-gray-100 hover:bg-gray-50"
                                data-invoice="{{ strtolower($trx->invoice_no) }}"
                                data-layanan="{{ strtolower($trx->jenis_layanan) }}"
                                data-status="{{ strtolower($trx->status_proses) }}"
                                data-tanggal="{{ \Carbon\Carbon::parse($trx->tgl_masuk)->format('Y-m-d') }}"
                            >
                                {{-- Invoice --}}
                                <td class="px-5 py-4">
                                    <span class="font-semibold text-gray-800">
                                        {{ $trx->invoice_no }}
                                    </span>
                                </td>

                                {{-- Kendaraan & Client --}}
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-gray-800">
                                        {{ $trx->vehicle->nopol ?? '-' }}
                                    </div>

                                    <div class="mt-1 text-xs text-gray-500">
                                        {{ $trx->vehicle->client->nama_lengkap ?? '-' }}
                                    </div>
                                </td>

                                {{-- Layanan --}}
                                <td class="px-5 py-4">{{ $trx->jenis_layanan }}</td>

                                {{-- Total --}}
                                <td class="px-5 py-4 font-medium text-gray-800">
                                    Rp {{ number_format($trx->total_biaya, 0, ',', '.') }}
                                </td>

                                {{-- Status --}}
                                <td class="px-5 py-4">
                                    @if ($trx->status_proses == 'Selesai')
                                        <span
                                            class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700"
                                        >
                                            {{ $trx->status_proses }}
                                        </span>

                                    @else
                                        <span
                                            class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700"
                                        >
                                            {{ $trx->status_proses }}
                                        </span>

                                    @endif
                                </td>

                                {{-- Tanggal --}}
                                <td class="whitespace-nowrap px-5 py-4">
                                    {{ \Carbon\Carbon::parse($trx->tgl_masuk)->format('d/m/Y') }}
                                </td>

                                {{-- Aksi --}}
                                <td class="px-5 py-4">
                                    <div
                                        class="flex items-center justify-center gap-3 whitespace-nowrap"
                                    >
                                        <a
                                            href="{{ route('transactions.edit', $trx->id) }}"
                                            class="text-sm font-medium text-indigo-600 hover:text-indigo-800"
                                        >
                                            Update Status
                                        </a>

                                        <form
                                            action="{{ route('transactions.destroy', $trx->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Hapus transaksi ini?');"
                                        >
                                            @csrf
                                            @method ('DELETE')

                                            <button
                                                type="submit"
                                                class="text-sm font-medium text-red-600 hover:text-red-800"
                                            >
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                        @empty
                            <tr id="emptyData">
                                <td colspan="7" class="px-5 py-10 text-center text-gray-500">
                                    Belum ada transaksi.
                                </td>
                            </tr>

                        @endforelse

                        {{-- Jika hasil filter tidak ditemukan --}}
                        <tr id="noFilterResult" class="hidden">
                            <td colspan="7" class="px-5 py-10 text-center text-gray-500">
                                Tidak ada transaksi yang sesuai dengan filter.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- JAVASCRIPT FILTER --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterInvoice = document.getElementById('filterInvoice');
            const filterLayanan = document.getElementById('filterLayanan');
            const filterStatus = document.getElementById('filterStatus');
            const filterTanggal = document.getElementById('filterTanggal');
            const resetFilter = document.getElementById('resetFilter');

            const rows = document.querySelectorAll('.transaction-row');
            const noResult = document.getElementById('noFilterResult');

            function filterTable() {
                const invoice = filterInvoice.value.toLowerCase().trim();
                const layanan = filterLayanan.value.toLowerCase();
                const status = filterStatus.value.toLowerCase();
                const tanggal = filterTanggal.value;

                let visibleRows = 0;

                rows.forEach(function (row) {
                    const rowInvoice = row.dataset.invoice;
                    const rowLayanan = row.dataset.layanan;
                    const rowStatus = row.dataset.status;
                    const rowTanggal = row.dataset.tanggal;

                    const matchInvoice = invoice === '' || rowInvoice.includes(invoice);

                    const matchLayanan = layanan === '' || rowLayanan === layanan;

                    const matchStatus = status === '' || rowStatus === status;

                    const matchTanggal = tanggal === '' || rowTanggal === tanggal;

                    if (matchInvoice && matchLayanan && matchStatus && matchTanggal) {
                        row.classList.remove('hidden');
                        visibleRows++;
                    } else {
                        row.classList.add('hidden');
                    }
                });

                if (visibleRows === 0 && rows.length > 0) {
                    noResult.classList.remove('hidden');
                } else {
                    noResult.classList.add('hidden');
                }
            }

            // Jalankan filter ketika input berubah
            filterInvoice.addEventListener('input', filterTable);

            filterLayanan.addEventListener('change', filterTable);

            filterStatus.addEventListener('change', filterTable);

            filterTanggal.addEventListener('change', filterTable);

            // Reset
            resetFilter.addEventListener('click', function () {
                filterInvoice.value = '';
                filterLayanan.value = '';
                filterStatus.value = '';
                filterTanggal.value = '';

                filterTable();
            });
        });
    </script>

@endsection
