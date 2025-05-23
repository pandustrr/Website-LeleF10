<div class="bg-white rounded-lg shadow p-6 mb-6 border-l-4 border-blue-500">
    <h2 class="text-lg font-semibold mb-4">Analisis Keuangan Harian</h2>

    @if (isset($predictions['error']))
        <!-- Error Message -->
        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-red-400 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
                <div>
                    <h3 class="text-sm font-medium text-red-700">Gagal membuat prediksi</h3>
                    <p class="text-sm text-red-600 mt-1">{{ $predictions['error'] }}</p>
                </div>
            </div>
        </div>
    @elseif(!empty($predictions) && is_array($predictions['dates']) && count($predictions['dates']) > 0)
        <!-- Daily Progress Section -->
        <div class="mb-6">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <div class="bg-red-50 p-4 rounded-lg border border-red-100">
                    <p class="text-sm text-gray-600 mb-1">Total Pengeluaran</p>
                    <p class="font-bold text-red-600 text-xl">
                        Rp {{ number_format(end($predictions['pengeluaran']['historical']), 0, ',', '.') }}
                    </p>

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

                <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                    <p class="text-sm text-gray-600 mb-1">Total Pemasukan</p>
                    <p class="font-bold text-green-600 text-xl">
                        Rp {{ number_format(end($predictions['pemasukan']['historical']), 0, ',', '.') }}
                    </p>
                    <div class="mt-4 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span>Panen:</span>
                            <span>Rp
                                {{ $siklusAktif ? number_format($siklusAktif->panens->sum('total_harga'), 0, ',', '.') : 0 }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                    <p class="text-sm text-gray-600 mb-1">Profit Saat Ini</p>
                    <p class="font-bold text-blue-600 text-xl">
                        Rp {{ number_format(end($predictions['profit']['historical']), 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Forecast Section -->
        <div class="mt-8">
            <h3 class="text-md font-medium mb-2">Prediksi 7 Hari Ke Depan</h3>
            <div class="chart-container relative h-96 w-full">
                <canvas id="forecastChart"></canvas>
            </div>

            <!-- Forecast Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <!-- Pengeluaran Prediction -->
                <div class="bg-red-50 p-4 rounded-lg border border-red-100">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Prediksi Pengeluaran</p>
                            <p class="font-bold text-red-600 text-xl">
                                Rp
                                {{ number_format(array_sum($predictions['pengeluaran']['predicted'] ?? []), 0, ',', '.') }}
                            </p>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-full bg-red-100 text-red-800">
                            RMSE: {{ number_format($predictions['pengeluaran']['metrics']['rmse'] ?? 0, 2) }}
                        </span>
                    </div>
                </div>

                <!-- Pemasukan Prediction -->
                <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Prediksi Pemasukan</p>
                            <p class="font-bold text-green-600 text-xl">
                                Rp
                                {{ number_format(array_sum($predictions['pemasukan']['predicted'] ?? []), 0, ',', '.') }}
                            </p>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-800">
                            MAPE: {{ number_format($predictions['pemasukan']['metrics']['mape'] ?? 0, 2) }}%
                        </span>
                    </div>
                </div>

                <!-- Profit Prediction -->
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Prediksi Profit</p>
                            <p class="font-bold text-blue-600 text-xl">
                                Rp
                                {{ number_format(array_sum($predictions['profit']['predicted'] ?? []), 0, ',', '.') }}
                            </p>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-800">
                            Akurasi: {{ number_format(100 - ($predictions['profit']['metrics']['mape'] ?? 0), 2) }}%
                        </span>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Format currency
                    const formatCurrency = (value) => {
                        return 'Rp' + value.toLocaleString('id-ID');
                    };

                    // Format dates for display
                    const formatDates = (dates) => {
                        return dates.map(date => {
                            if (!date) return '';
                            const [day, month] = date.split('/');
                            return `${day}/${month}`;
                        });
                    };

                    // Prepare data
                    const dailyData = {
                        labels: formatDates(@json($predictions['dates'] ?? [])),
                        pengeluaran: @json($predictions['pengeluaran']['historical'] ?? []),
                        pemasukan: @json($predictions['pemasukan']['historical'] ?? []),
                        profit: @json($predictions['profit']['historical'] ?? [])
                    };

                    const forecastData = {
                        labels: formatDates([
                            ...@json($predictions['dates'] ?? []),
                            ...@json($predictions['future_dates'] ?? [])
                        ]),
                        pengeluaran: {
                            actual: [...dailyData.pengeluaran, ...Array(7).fill(null)],
                            predicted: [...Array(dailyData.pengeluaran.length).fill(null),
                                ...@json($predictions['pengeluaran']['predicted'] ?? [])
                            ]
                        },
                        pemasukan: {
                            actual: [...dailyData.pemasukan, ...Array(7).fill(null)],
                            predicted: [...Array(dailyData.pemasukan.length).fill(null),
                                ...@json($predictions['pemasukan']['predicted'] ?? [])
                            ]
                        },
                        profit: {
                            actual: [...dailyData.profit, ...Array(7).fill(null)],
                            predicted: [...Array(dailyData.profit.length).fill(null),
                                ...@json($predictions['profit']['predicted'] ?? [])
                            ]
                        }
                    };

                    // Common chart options
                    const chartOptions = {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    boxWidth: 12,
                                    padding: 20,
                                    usePointStyle: true,
                                }
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) label += ': ';
                                        if (context.raw !== null) {
                                            label += formatCurrency(context.raw);
                                        }
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: false,
                                ticks: {
                                    callback: function(value) {
                                        return formatCurrency(value);
                                    }
                                },
                                grid: {
                                    drawBorder: false
                                }
                            },
                            x: {
                                grid: {
                                    display: false,
                                    drawBorder: false
                                }
                            }
                        },
                        elements: {
                            point: {
                                radius: 3,
                                hoverRadius: 5
                            }
                        }
                    };

                    // Create Daily Progress Chart
                    const dailyCtx = document.getElementById('dailyProgressChart');
                    if (dailyCtx) {
                        new Chart(dailyCtx, {
                            type: 'line',
                            data: {
                                labels: dailyData.labels,
                                datasets: [{
                                        label: 'Pengeluaran',
                                        data: dailyData.pengeluaran,
                                        borderColor: '#ef4444',
                                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                        borderWidth: 2,
                                        tension: 0.3,
                                        fill: true
                                    },
                                    {
                                        label: 'Pemasukan',
                                        data: dailyData.pemasukan,
                                        borderColor: '#10b981',
                                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                        borderWidth: 2,
                                        tension: 0.3,
                                        fill: true
                                    },
                                    {
                                        label: 'Profit',
                                        data: dailyData.profit,
                                        borderColor: '#3b82f6',
                                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                        borderWidth: 2,
                                        tension: 0.3,
                                        fill: true
                                    }
                                ]
                            },
                            options: chartOptions
                        });
                    }

                    // Create Forecast Chart
                    const forecastCtx = document.getElementById('forecastChart');
                    if (forecastCtx) {
                        new Chart(forecastCtx, {
                            type: 'line',
                            data: {
                                labels: forecastData.labels,
                                datasets: [{
                                        label: 'Pengeluaran Aktual',
                                        data: forecastData.pengeluaran.actual,
                                        borderColor: '#ef4444',
                                        backgroundColor: 'transparent',
                                        borderWidth: 2,
                                        tension: 0.3
                                    },
                                    {
                                        label: 'Prediksi Pengeluaran',
                                        data: forecastData.pengeluaran.predicted,
                                        borderColor: '#ef4444',
                                        borderDash: [5, 5],
                                        backgroundColor: 'transparent',
                                        borderWidth: 2,
                                        tension: 0.3
                                    },
                                    {
                                        label: 'Pemasukan Aktual',
                                        data: forecastData.pemasukan.actual,
                                        borderColor: '#10b981',
                                        backgroundColor: 'transparent',
                                        borderWidth: 2,
                                        tension: 0.3
                                    },
                                    {
                                        label: 'Prediksi Pemasukan',
                                        data: forecastData.pemasukan.predicted,
                                        borderColor: '#10b981',
                                        borderDash: [5, 5],
                                        backgroundColor: 'transparent',
                                        borderWidth: 2,
                                        tension: 0.3
                                    },
                                    {
                                        label: 'Profit Aktual',
                                        data: forecastData.profit.actual,
                                        borderColor: '#3b82f6',
                                        backgroundColor: 'transparent',
                                        borderWidth: 2,
                                        tension: 0.3
                                    },
                                    {
                                        label: 'Prediksi Profit',
                                        data: forecastData.profit.predicted,
                                        borderColor: '#3b82f6',
                                        borderDash: [5, 5],
                                        backgroundColor: 'transparent',
                                        borderWidth: 2,
                                        tension: 0.3
                                    }
                                ]
                            },
                            options: {
                                ...chartOptions,
                                plugins: {
                                    ...chartOptions.plugins,
                                    annotation: {
                                        annotations: {
                                            zeroLine: {
                                                type: 'line',
                                                yMin: 0,
                                                yMax: 0,
                                                borderColor: '#6b7280',
                                                borderWidth: 1,
                                                borderDash: [4, 4]
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }
                });
            </script>
        @endpush
    @else
        <!-- No Data Warning -->
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-yellow-400 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd" />
                </svg>
                <div>
                    <p class="text-sm text-yellow-700 font-medium">Data tidak cukup untuk analisis</p>
                    <p class="text-xs text-yellow-600 mt-1">
                        Dibutuhkan minimal 1 transaksi dalam siklus ini untuk membuat prediksi dasar.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Chart Explanation -->
    <div class="mt-6 text-sm text-gray-600">
        <p><strong>Keterangan:</strong></p>
        <ul class="list-disc pl-5 mt-2 space-y-1">
            <li>Garis solid menunjukkan data aktual yang sudah terjadi</li>
            <li>Garis putus-putus menunjukkan hasil prediksi untuk 7 hari ke depan</li>
            <li><strong>RMSE</strong> (Root Mean Square Error) mengukur akurasi prediksi dalam nominal (semakin kecil
                semakin baik)</li>
            <li><strong>MAPE</strong> (Mean Absolute Percentage Error) mengukur persentase kesalahan prediksi (semakin
                kecil semakin baik)</li>
            <li>Prediksi akan diperbarui otomatis saat ada perubahan data transaksi</li>
        </ul>
    </div>
</div>

<style>
    .chart-container {
        position: relative;
        height: 400px;
        width: 100%;
    }

    canvas {
        width: 100% !important;
        height: 100% !important;
    }
</style>
