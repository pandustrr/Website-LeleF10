<div class="bg-white rounded-lg shadow overflow-hidden">
    <h2 class="text-lg font-semibold p-4 bg-gray-50">Detail Keuangan</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">No</th>
                    <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Tanggal</th>
                    <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                    <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                    <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Kuantitas</th>
                    <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Harga</th>
                    <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($transaksi as $index => $trx)
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-2 whitespace-nowrap">{{ $index + 1 }}</td>
                        <td class="px-3 py-2 whitespace-nowrap">
                            {{ $trx['tanggal'] ? \Carbon\Carbon::parse($trx['tanggal'])->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-3 py-2">{{ $trx['kategori'] }}</td>
                        <td class="px-3 py-2">{{ $trx['tipe'] }}</td>
                        <td class="px-3 py-2 whitespace-nowrap">{{ $trx['kuantitas'] }} kg</td>
                        <td class="px-3 py-2 whitespace-nowrap">Rp {{ number_format($trx['harga'], 0, ',', '.') }}</td>
                        <td class="px-3 py-2">
                            <span class="px-2 py-1 text-xs rounded-full {{ $trx['jenis'] == 'pemasukan' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
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
