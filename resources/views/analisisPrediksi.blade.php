@extends('layouts.app')

@section('content')
    <div class="p-4 sm:p-6 bg-gray-50 min-h-screen">
        <!-- Header Section -->
        <div class="mb-6 sm:mb-8">
            <div class="flex flex-col">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Analisis & Prediksi FCR</h1>
                    <p class="text-gray-600 mt-1 sm:mt-2 text-sm sm:text-base">Monitor efisiensi pakan menggunakan sistem
                        fuzzy logic berbasis data historis</p>
                </div>
            </div>

            <!-- Breadcrumb Navigation -->
            <nav class="flex mt-4 sm:mt-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center text-xs sm:text-sm font-medium text-gray-700 hover:text-blue-600">
                            <svg class="w-3 h-3 mr-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 9 4-4-4-4" />
                            </svg>
                            <span class="ml-1 text-xs sm:text-sm font-medium text-gray-500 md:ml-2">Analisis FCR</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        {{-- Bagian Error Handling --}}
        @if (isset($analysis['error']))
            <!-- Error Message -->
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Gagal memuat analisis</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <p>{{ $analysis['error'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            {{-- Bagian Summary Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
                <!-- Card Total Pakan -->
                <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Total Pakan</p>
                                <p class="mt-1 text-2xl sm:text-3xl font-semibold text-gray-900">
                                    {{ number_format($totalPakan) }} kg
                                </p>
                            </div>
                            <div class="bg-orange-100 p-2 sm:p-3 rounded-md sm:rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6 text-orange-600"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                        </div>
                        <div class="mt-2 sm:mt-4">
                            <p class="text-xs text-gray-500">Dari {{ count($historisData['pakan']) }} pencatatan</p>
                        </div>
                    </div>
                </div>

                <!-- Card Total Panen -->
                <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Total Panen</p>
                                <p class="mt-1 text-2xl sm:text-3xl font-semibold text-gray-900">
                                    {{ number_format($totalPanen) }} kg
                                </p>
                            </div>
                            <div class="bg-green-100 p-2 sm:p-3 rounded-md sm:rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6 text-green-600"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                        </div>
                        <div class="mt-2 sm:mt-4">
                            <p class="text-xs text-gray-500">Dari {{ count($historisData['panen']) }} pencatatan</p>
                        </div>
                    </div>
                </div>

                <!-- Card FCR Progress -->
                <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">FCR Progress</p>
                                <p class="mt-1 text-2xl sm:text-3xl font-semibold text-gray-900">
                                    @if (count($historisData['fcr']) > 0)
                                        {{ number_format(array_sum($historisData['fcr']) / count($historisData['fcr']), 2) }}
                                    @else
                                        0.00
                                    @endif
                                </p>
                                <div class="mt-1 sm:mt-2 text-xs sm:text-sm text-gray-500">
                                    <span class="font-medium">Detail:</span>
                                    <div class="flex flex-wrap gap-1 sm:gap-2 mt-1">
                                        <span
                                            class="text-xs px-1.5 sm:px-2 py-0.5 sm:py-1 bg-blue-100 text-blue-800 rounded">
                                            Data: {{ count($historisData['fcr']) }} siklus
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-blue-100 p-2 sm:p-3 rounded-md sm:rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6 text-blue-600"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card FCR Prediksi -->
                <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">FCR Prediksi (120 hari)</p>
                                <p class="mt-1 text-2xl sm:text-3xl font-semibold text-gray-900">
                                    @if (isset($analysis['predicted_fcr']))
                                        {{ number_format($analysis['predicted_fcr'], 2) }}
                                    @else
                                        0.00
                                    @endif
                                </p>
                                <div class="mt-1 sm:mt-2 text-xs sm:text-sm text-gray-500">
                                    <span class="font-medium">Detail Prediksi:</span>
                                    <div class="flex flex-wrap gap-1 sm:gap-2 mt-1">
                                        @if (isset($analysis['chart_data']['datasets'][0]['data'][0]))
                                            <span
                                                class="text-xs px-1.5 sm:px-2 py-0.5 sm:py-1 bg-blue-100 text-blue-800 rounded">
                                                P1:
                                                {{ number_format($analysis['chart_data']['datasets'][0]['data'][0], 2) }}
                                            </span>
                                        @endif
                                        @if (isset($analysis['chart_data']['datasets'][0]['data'][1]))
                                            <span
                                                class="text-xs px-1.5 sm:px-2 py-0.5 sm:py-1 bg-blue-200 text-blue-800 rounded">
                                                P2:
                                                {{ number_format($analysis['chart_data']['datasets'][0]['data'][1], 2) }}
                                            </span>
                                        @endif
                                        @if (isset($analysis['chart_data']['datasets'][0]['data'][2]))
                                            <span
                                                class="text-xs px-1.5 sm:px-2 py-0.5 sm:py-1 bg-blue-300 text-blue-800 rounded">
                                                P3:
                                                {{ number_format($analysis['chart_data']['datasets'][0]['data'][2], 2) }}
                                            </span>
                                        @endif
                                    </div>
                                    @if (isset($analysis['fuzzy_details']['prediction_method']))
                                        <p class="mt-1 text-xs text-gray-400">
                                            Metode:
                                            {{ $analysis['fuzzy_details']['prediction_method'] == 'linear_regression' ? 'Regresi Linear' : 'Proyeksi Sederhana' }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="bg-purple-100 p-2 sm:p-3 rounded-md sm:rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6 text-purple-600"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Grafik Perkembangan FCR --}}
            <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6 sm:mb-8">
                <div class="p-4 sm:p-6 border-b border-gray-100">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 sm:gap-0">
                        <div>
                            <h3 class="text-base sm:text-lg font-medium text-gray-900">Perkembangan FCR</h3>
                            <p class="mt-0.5 sm:mt-1 text-xs sm:text-sm text-gray-500">Data historis pakan dan panen dari
                                semua siklus</p>
                        </div>
                        <a href="{{ route('produksi') }}"
                            class="text-xs sm:text-sm text-blue-600 hover:text-blue-800 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-4 sm:w-4 mr-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Data
                        </a>
                    </div>
                </div>
                <div class="p-4 sm:p-6">
                    @include('components.analisisPrediksi.chart-progress')
                </div>
            </div>

            {{-- Grafik Prediksi FCR --}}
            <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6 sm:mb-8">
                <div class="p-4 sm:p-6 border-b border-gray-100">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 sm:gap-0">
                        <div>
                            <h3 class="text-base sm:text-lg font-medium text-gray-900">Prediksi FCR</h3>
                            <p class="mt-0.5 sm:mt-1 text-xs sm:text-sm text-gray-500">Perkiraan nilai FCR 7 hari ke depan
                                berdasarkan pola historis</p>
                        </div>
                    </div>
                </div>
                <div class="p-4 sm:p-6">
                    @include('components.analisisPrediksi.chart-prediksi')
                </div>
            </div>

            <!-- Tambahkan ini di bagian Summary Cards (setelah Card FCR Prediksi) -->
<!-- Card FCR Saat Ini -->
<div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-4 sm:p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">FCR Saat Ini</p>
                <p class="mt-1 text-2xl sm:text-3xl font-semibold text-gray-900">
                    @if (count($historisData['fcr']) > 0)
                        {{ number_format(end($historisData['fcr']), 2) }}
                    @else
                        0.00
                    @endif
                </p>
                <div class="mt-1 sm:mt-2 text-xs sm:text-sm text-gray-500">
                    <span class="font-medium">Status:</span>
                    @php
                        $currentFcr = count($historisData['fcr']) > 0 ? end($historisData['fcr']) : 0;
                        $currentStatus = 'tidak diketahui';
                        $statusColor = 'bg-gray-100 text-gray-800';

                        if ($currentFcr > 0) {
                            if ($currentFcr <= $fcrStandar['baik']) {
                                $currentStatus = 'Baik';
                                $statusColor = 'bg-green-100 text-green-800';
                            } elseif ($currentFcr <= $fcrStandar['sedang']) {
                                $currentStatus = 'Sedang';
                                $statusColor = 'bg-yellow-100 text-yellow-800';
                            } else {
                                $currentStatus = 'Buruk';
                                $statusColor = 'bg-red-100 text-red-800';
                            }
                        }
                    @endphp
                    <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full {{ $statusColor }}">
                        {{ $currentStatus }}
                    </span>
                </div>
            </div>
            <div class="bg-indigo-100 p-2 sm:p-3 rounded-md sm:rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6 text-indigo-600"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
            </div>
        </div>
    </div>
</div>

            {{-- Rekomendasi Berdasarkan Prediksi --}}
            <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6 sm:mb-8">
                <div class="p-4 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div class="pr-2 w-full">
                            <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Rekomendasi Berdasarkan
                                Prediksi</p>
                            <p class="mt-1 text-base sm:text-lg font-semibold text-gray-900">
                                {{ $analysis['recommendation'] ?? 'Tidak tersedia' }}
                            </p>

                            {{-- Detail Prediksi --}}
                            <div class="mt-4 text-sm sm:text-base text-gray-700 space-y-1">
                                <p>
                                    <span class="font-medium text-gray-600">Prediksi FCR:</span>
                                    {{ number_format($analysis['predicted_fcr'] ?? 0, 2) }}
                                    @php
                                        $predictedFcr = $analysis['predicted_fcr'] ?? null;
                                        $predictedStatus = 'tidak diketahui';
                                        if ($predictedFcr !== null) {
                                            $predictedStatus = match (true) {
                                                $predictedFcr < 1.0 => 'baik',
                                                $predictedFcr < 1.5 => 'sedang',
                                                default => 'buruk',
                                            };
                                        }
                                        $predictedColor = match ($predictedStatus) {
                                            'baik' => 'text-green-600',
                                            'sedang' => 'text-yellow-600',
                                            'buruk' => 'text-red-600',
                                            default => 'text-gray-600',
                                        };
                                    @endphp
                                    <span class="ml-2 font-semibold {{ $predictedColor }}">
                                        ({{ ucfirst($predictedStatus) }})
                                    </span>
                                </p>
                            </div>

                            {{-- Progress Bar Tren FCR --}}
                            <div class="mt-4">
                                <label class="text-sm text-gray-500 font-medium">Perkiraan Tren FCR (rendah lebih
                                    baik)</label>
                                @php
                                    $progress = min(100, max(0, (($predictedFcr ?? 1.5) / 2.0) * 100));
                                    $progressColor = match (true) {
                                        $progress < 50 => 'bg-green-500',
                                        $progress < 75 => 'bg-yellow-500',
                                        default => 'bg-red-500',
                                    };
                                @endphp
                                <div class="w-full h-3 bg-gray-200 rounded-full mt-1">
                                    <div class="h-full rounded-full {{ $progressColor }}"
                                        style="width: {{ $progress }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabel Data Historis --}}
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

            {{-- Penjelasan Fuzzy Logic --}}
            <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 sm:p-6 border-b border-gray-100">
                    <h3 class="text-base sm:text-lg font-medium text-gray-900">Tentang Analisis Fuzzy</h3>
                    <p class="mt-0.5 sm:mt-1 text-xs sm:text-sm text-gray-500">Bagaimana sistem menghasilkan rekomendasi
                    </p>
                </div>
                <div class="p-4 sm:p-6">
                    <div class="prose max-w-none text-sm sm:text-base">
                        <h4 class="text-gray-900 text-base sm:text-lg">Apa itu FCR?</h4>
                        <p class="text-gray-700">
                            Feed Conversion Ratio (FCR) adalah rasio antara jumlah pakan yang diberikan dengan berat ikan
                            yang dihasilkan. Semakin rendah nilai FCR, semakin efisien penggunaan pakan.
                        </p>

                        <h4 class="text-gray-900 mt-3 sm:mt-4 text-base sm:text-lg">Kategori FCR</h4>
                        <ul class="list-disc pl-5 mt-1 sm:mt-2 space-y-1">
                            <li class="text-gray-700">
                                <span class="font-medium text-green-600">Baik</span>: Nilai ≤ {{ $fcrStandar['baik'] }}
                                (Efisiensi tinggi)
                            </li>
                            <li class="text-gray-700">
                                <span class="font-medium text-yellow-600">Sedang</span>: Nilai {{ $fcrStandar['baik'] }} -
                                {{ $fcrStandar['sedang'] }} (Efisiensi normal)
                            </li>
                            <li class="text-gray-700">
                                <span class="font-medium text-red-600">Buruk</span>: Nilai ≥ {{ $fcrStandar['sedang'] }}
                                (Efisiensi rendah)
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
