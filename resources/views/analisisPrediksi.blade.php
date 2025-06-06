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

            {{-- <!-- Breadcrumb Navigation -->
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
            </nav> --}}
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
                            <p>Belum ada data yang di input, tidak bisa melihat analisis</p>
                        </div>
                    </div>
                </div>
            </div>
        @else

            @include('components.analisisPrediksi.cards.info-cards')

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

            {{-- Include Recommendation Cards --}}
            @include('components.analisisPrediksi.cards.rekomendasi-cards')

            {{-- Tabel Data Historis --}}
            @include('components.analisisPrediksi.tables.histori')

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
