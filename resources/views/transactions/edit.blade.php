@extends ('layouts.app')

@section ('title', 'Update Status Transaksi')
@section ('header_title', 'Update Status & Biaya Transaksi')

@section ('content')
    <div class="mx-auto max-w-4xl">
        {{-- Informasi Transaksi --}}
        <div class="mb-6 rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <h2 class="mb-4 text-base font-semibold text-gray-800">Informasi Transaksi</h2>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                {{-- No Invoice --}}
                <div>
                    <p class="text-xs font-medium text-gray-500">No Invoice</p>

                    <p class="mt-1 text-sm font-semibold text-gray-800">
                        {{ $transaction->invoice_no }}
                    </p>
                </div>

                {{-- Kendaraan --}}
                <div>
                    <p class="text-xs font-medium text-gray-500">Kendaraan</p>

                    <p class="mt-1 text-sm font-semibold text-gray-800">
                        {{ $transaction->vehicle->nopol ?? '-' }}
                    </p>

                    <p class="text-xs text-gray-500">
                        {{ $transaction->vehicle->client->nama_lengkap ?? '-' }}
                    </p>
                </div>

                {{-- Layanan --}}
                <div>
                    <p class="text-xs font-medium text-gray-500">Layanan</p>

                    <p class="mt-1 text-sm font-semibold text-gray-800">
                        {{ $transaction->jenis_layanan }}
                    </p>
                </div>
            </div>
        </div>

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

        {{-- Form Update --}}
        <form
            action="{{ route('transactions.update', $transaction->id) }}"
            method="POST"
            class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm"
        >
            @csrf
            @method ('PUT')

            {{-- Status Proses --}}
            <div class="mb-5">
                <label for="status_proses" class="mb-2 block text-sm font-semibold text-gray-700">
                    Status Proses <span class="text-red-500">*</span>
                </label>

                <select
                    id="status_proses"
                    name="status_proses"
                    required
                    class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                >
                    @foreach ($statuses as $status)
                        <option
                            value="{{ $status }}"
                            {{ $transaction->status_proses == $status ? 'selected' : '' }}
                        >
                            {{ $status }}
                        </option>

                    @endforeach
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
                        value="{{ old('total_biaya', $transaction->total_biaya) }}"
                        required
                        min="0"
                        class="block w-full rounded-lg border border-gray-300 py-2.5 pl-10 pr-3 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    />
                </div>
            </div>

            {{-- Tanggal Selesai --}}
            <div class="mb-6">
                <label for="tgl_selesai" class="mb-2 block text-sm font-semibold text-gray-700">
                    Tanggal Selesai
                    <span class="font-normal text-gray-400"> (Opsional) </span>
                </label>

                <input
                    id="tgl_selesai"
                    type="date"
                    name="tgl_selesai"
                    value="{{ old('tgl_selesai', $transaction->tgl_selesai) }}"
                    class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                />

                <p class="mt-2 text-xs text-gray-500">Jika status diubah menjadi
                <span class="font-medium text-gray-700">Selesai</span>
                dan tanggal dikosongkan, tanggal akan otomatis diisi dengan hari ini.</p>
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
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

@endsection
