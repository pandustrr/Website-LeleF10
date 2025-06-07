<div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6 sm:mb-8">
    <div class="p-4 sm:p-6 border-b border-gray-100">
        <h3 class="text-base sm:text-lg font-medium text-gray-900">Data Historis Siklus Budidaya</h3>
        <p class="mt-0.5 sm:mt-1 text-xs sm:text-sm text-gray-500">Detail pakan, panen, dan FCR per siklus</p>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Siklus</th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Periode</th>
                    <th scope="col"
                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Pakan (kg)</th>
                    <th scope="col"
                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Panen (kg)</th>
                    <th scope="col"
                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        FCR</th>
                    <th scope="col"
                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($historisData['data_details'] as $data)
                    @php
                        $fcrStatus = match (true) {
                            $data['fcr'] <= $fcrStandar['baik'] => 'Baik',
                            $data['fcr'] <= $fcrStandar['sedang'] => 'Sedang',
                            default => 'Buruk',
                        };
                        $statusColor = match ($fcrStatus) {
                            'Baik' => 'bg-green-100 text-green-800',
                            'Sedang' => 'bg-yellow-100 text-yellow-800',
                            default => 'bg-red-100 text-red-800',
                        };
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $data['siklus_name'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($data['tanggal_mulai'])->format('d M Y') }} -
                            {{ \Carbon\Carbon::parse($data['tanggal_akhir'])->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500">
                            {{ number_format($data['pakan'], 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500">
                            {{ number_format($data['panen'], 2) }}</td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium {{ $data['fcr'] > 0 ? ($fcrStatus == 'Baik' ? 'text-green-600' : ($fcrStatus == 'Sedang' ? 'text-yellow-600' : 'text-red-600')) : 'text-gray-500' }}">
                            {{ $data['fcr'] > 0 ? number_format($data['fcr'], 2) : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if ($data['fcr'] > 0)
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColor }}">
                                    {{ $fcrStatus }}
                                </span>
                            @else
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Tidak Ada Data
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
