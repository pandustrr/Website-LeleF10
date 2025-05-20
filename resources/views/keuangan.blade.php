@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-6">Laporan Keuangan</h1>

        <!-- Dropdown Siklus -->
        <div class="mb-6">
            <form method="GET" action="{{ route('keuangan') }}">
                <div class="flex items-center">
                    <label for="siklus_id" class="mr-2">Pilih Siklus:</label>
                    <select name="siklus_id" id="siklus_id" class="form-select rounded-md" onchange="this.form.submit()">
                        @foreach ($siklusList as $siklus)
                            <option value="{{ $siklus->id }}"
                                {{ $siklusAktif && $siklus->id == $siklusAktif->id ? 'selected' : '' }}>
                                {{ $siklus->nama_siklus }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>



        <!-- Card Simulasi Panen -->
        @include('components.keuangan.simulasi-panen')

        <!-- Card Informasi Keuangan -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Card Pengeluaran -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
                <h2 class="text-lg font-semibold mb-2">Total Pengeluaran</h2>
                <div class="text-2xl font-bold text-red-600">
                    Rp {{ $siklusAktif ? number_format($siklusAktif->total_pengeluaran, 0, ',', '.') : 0 }}
                </div>
                <div class="mt-4 text-sm text-gray-600">
                    <div class="flex justify-between">
                        <span>Bibit:</span>
                        <span>Rp
                            {{ $siklusAktif ? number_format($siklusAktif->bibits->sum('total_harga'), 0, ',', '.') : 0 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Pakan:</span>
                        <span>Rp
                            {{ $siklusAktif ? number_format($siklusAktif->pakans->sum('total_harga'), 0, ',', '.') : 0 }}</span>
                    </div>
                </div>
            </div>

            <!-- Card Pemasukan -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                <h2 class="text-lg font-semibold mb-2">Total Pemasukan</h2>
                <div class="text-2xl font-bold text-green-600">
                    Rp {{ $siklusAktif ? number_format($siklusAktif->total_pemasukan, 0, ',', '.') : 0 }}
                </div>
                <div class="mt-4 text-sm text-gray-600">
                    <div class="flex justify-between">
                        <span>Panen:</span>
                        <span>Rp
                            {{ $siklusAktif ? number_format($siklusAktif->panens->sum('total_harga'), 0, ',', '.') : 0 }}</span>
                    </div>
                </div>
            </div>

            <!-- Card Laba/Rugi -->
            <div
                class="bg-white rounded-lg shadow p-6 border-l-4 border-{{ $siklusAktif && $siklusAktif->laba >= 0 ? 'green' : 'red' }}-500">
                <h2 class="text-lg font-semibold mb-2">Profit/Rugi</h2>
                <div class="text-2xl font-bold text-{{ $siklusAktif && $siklusAktif->laba >= 0 ? 'green' : 'red' }}-600">
                    Rp {{ $siklusAktif ? number_format($siklusAktif->laba, 0, ',', '.') : 0 }}
                </div>
            </div>
        </div>

        <!-- Tabel Transaksi -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <h2 class="text-lg font-semibold p-4 bg-gray-50">Detail Transaksi</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kuantitas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($transaksi as $index => $trx)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $trx['tanggal'] ? \Carbon\Carbon::parse($trx['tanggal'])->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $trx['kategori'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $trx['tipe'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $trx['kuantitas'] }} kg</td>
                                <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($trx['harga'], 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 py-1 text-xs rounded-full bg-{{ $trx['jenis'] == 'pemasukan' ? 'green' : 'red' }}-100 text-{{ $trx['jenis'] == 'pemasukan' ? 'green' : 'red' }}-800">
                                        {{ $trx['jenis'] == 'pemasukan' ? 'Pemasukan' : 'Pengeluaran' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada data transaksi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
