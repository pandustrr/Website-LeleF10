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
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6 text-orange-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </div>
            </div>
            <div class="mt-2 sm:mt-4">
                <p class="text-xs text-gray-500">Dari {{ count($historisData['pakan']) }} pencatatan</p>
                {{-- <p class="mt-1 text-base sm:text-lg font-semibold text-gray-900">
                    <strong>Debug:</strong>
                    {{ json_encode($analysis) }}
                </p> --}}
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
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6 text-green-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
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
                            <span class="text-xs px-1.5 sm:px-2 py-0.5 sm:py-1 bg-blue-100 text-blue-800 rounded">
                                Data: {{ count($historisData['fcr']) }} siklus
                            </span>
                        </div>
                    </div>
                </div>
                <div class="bg-blue-100 p-2 sm:p-3 rounded-md sm:rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6 text-blue-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
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
                                <span class="text-xs px-1.5 sm:px-2 py-0.5 sm:py-1 bg-blue-100 text-blue-800 rounded">
                                    P1:
                                    {{ number_format($analysis['chart_data']['datasets'][0]['data'][0], 2) }}
                                </span>
                            @endif
                            @if (isset($analysis['chart_data']['datasets'][0]['data'][1]))
                                <span class="text-xs px-1.5 sm:px-2 py-0.5 sm:py-1 bg-blue-200 text-blue-800 rounded">
                                    P2:
                                    {{ number_format($analysis['chart_data']['datasets'][0]['data'][1], 2) }}
                                </span>
                            @endif
                            @if (isset($analysis['chart_data']['datasets'][0]['data'][2]))
                                <span class="text-xs px-1.5 sm:px-2 py-0.5 sm:py-1 bg-blue-300 text-blue-800 rounded">
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
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6 text-purple-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>
