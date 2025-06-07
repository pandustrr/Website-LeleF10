<!-- Card FCR Saat Ini -->
<div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6 sm:mb-8">
    <div class="p-4 sm:p-6">
        <div class="flex items-center justify-between">
            <div class="pr-2 w-full">
                <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Rekomendasi Berdasarkan FCR Saat Ini</p>
                <p class="mt-1 text-base sm:text-lg font-semibold text-gray-900">
                    {{ $analysis['current_recommendation'] ?? 'Tidak tersedia' }}
                </p>

                {{-- Detail FCR Saat Ini --}}
                <div class="mt-4 text-sm sm:text-base text-gray-700 space-y-1">
                    <p>
                        <span class="font-medium text-gray-600">FCR Saat Ini:</span>
                        {{ number_format($analysis['current_fcr'] ?? 0, 2) }}
                        @php
                            $currentFcr = $analysis['current_fcr'] ?? null;
                            $currentStatus = $analysis['fuzzy_details']['current_fcr_category'] ?? 'unknown';
                            $currentColor = match ($currentStatus) {
                                'baik' => 'text-green-600',
                                'sedang' => 'text-yellow-600',
                                'buruk' => 'text-red-600',
                                default => 'text-gray-600',
                            };
                        @endphp
                        <span class="ml-2 font-semibold {{ $currentColor }}">
                            ({{ ucfirst($currentStatus) }})
                        </span>
                    </p>

                    @if (isset($analysis['fuzzy_details']['historical_data_used']) && $analysis['fuzzy_details']['historical_data_used'])
                        <p class="text-xs text-gray-500 italic">
                            * Analisis berdasarkan data historis
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Card Prediksi FCR -->
<div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6 sm:mb-8">
    <div class="p-4 sm:p-6">
        <div class="flex items-center justify-between">
            <div class="pr-2 w-full">
                <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Rekomendasi Berdasarkan Prediksi FCR</p>
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
                            $predictedStatus = $analysis['status'] ?? 'unknown';
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
            </div>
        </div>
    </div>
</div>
