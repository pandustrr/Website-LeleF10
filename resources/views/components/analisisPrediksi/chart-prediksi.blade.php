    <div class="w-full" style="height: 500px;">
        <canvas id="predictionChart"></canvas>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('predictionChart').getContext('2d');
            const prediksiData = @json($analysis['chart_data'] ?? null);
            const historisData = @json($historisData);

            // Warna untuk chart
            const colors = {
                fcrAktual: '#3B82F6', // blue-500
                fcrPrediksi1: '#93C5FD', // blue-300
                fcrPrediksi2: '#60A5FA', // blue-400
                fcrPrediksi3: '#1D4ED8', // blue-700
                standarBaik: '#059669', // emerald-600
                standarSedang: '#D97706', // amber-600
                noData: '#9CA3AF' // gray-400
            };

            // Format tanggal untuk prediksi
            const formatTanggal = (date) => {
                const options = { day: '2-digit', month: 'long' };
                return date.toLocaleDateString('id-ID', options);
            };

            const today = new Date();

            // Periode prediksi (total 120 hari dibagi 3)
            const prediksi1End = new Date(today);
            prediksi1End.setDate(today.getDate() + 40);

            const prediksi2End = new Date(prediksi1End);
            prediksi2End.setDate(prediksi1End.getDate() + 40);

            const prediksi3End = new Date(prediksi2End);
            prediksi3End.setDate(prediksi2End.getDate() + 40);

            // Ambil data prediksi dari Python
            const hasPrediction = prediksiData && prediksiData.datasets && prediksiData.datasets[0];
            let avgPrediction1 = 0;
            let avgPrediction2 = 0;
            let avgPrediction3 = 0;

            if (hasPrediction) {
                // Data dari Python (sudah dalam 3 periode)
                const predValues = prediksiData.datasets[0].data;
                avgPrediction1 = predValues[0] || 0;
                avgPrediction2 = predValues[1] || 0;
                avgPrediction3 = predValues[2] || 0;
            }

            // Siapkan label untuk chart
            const labels = [];
            const fcrValues = [];

            // Tambahkan data historis
            if (historisData && historisData.labels && historisData.fcr) {
                for (let i = 0; i < historisData.labels.length; i++) {
                    labels.push(historisData.labels[i]);
                    fcrValues.push(historisData.fcr[i]);
                }
            }

            // Tambahkan label prediksi
            labels.push(
                `Prediksi 1 (${formatTanggal(today)} - ${formatTanggal(prediksi1End)})`,
                `Prediksi 2 (${formatTanggal(prediksi1End)} - ${formatTanggal(prediksi2End)})`,
                `Prediksi 3 (${formatTanggal(prediksi2End)} - ${formatTanggal(prediksi3End)})`
            );

            // Data untuk chart
            const chartData = {
                labels: labels,
                datasets: [
                    {
                        label: 'Standar Baik (≤1.0)',
                        data: Array(labels.length).fill({{ $fcrStandar['baik'] }}),
                        type: 'line',
                        borderColor: colors.standarBaik,
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        pointRadius: 0
                    },
                    {
                        label: 'Standar Sedang ({{ $fcrStandar["sedang"] }})',
                        data: Array(labels.length).fill({{ $fcrStandar['sedang'] }}),
                        type: 'line',
                        borderColor: colors.standarSedang,
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        pointRadius: 0
                    },
                    {
                        label: 'FCR Aktual',
                        data: [...fcrValues, null, null, null],
                        backgroundColor: hexToRgba(colors.fcrAktual, 0.7),
                        borderColor: darkenColor(colors.fcrAktual, 20),
                        borderWidth: 1,
                        barPercentage: 0.6
                    },
                    {
                        label: 'FCR Prediksi 1',
                        data: [...Array(fcrValues.length).fill(null), avgPrediction1, null, null],
                        backgroundColor: hexToRgba(colors.fcrPrediksi1, 0.7),
                        borderColor: darkenColor(colors.fcrPrediksi1, 20),
                        borderWidth: 1,
                        barPercentage: 0.6
                    },
                    {
                        label: 'FCR Prediksi 2',
                        data: [...Array(fcrValues.length).fill(null), null, avgPrediction2, null],
                        backgroundColor: hexToRgba(colors.fcrPrediksi2, 0.7),
                        borderColor: darkenColor(colors.fcrPrediksi2, 20),
                        borderWidth: 1,
                        barPercentage: 0.6
                    },
                    {
                        label: 'FCR Prediksi 3',
                        data: [...Array(fcrValues.length).fill(null), null, null, avgPrediction3],
                        backgroundColor: hexToRgba(colors.fcrPrediksi3, 0.7),
                        borderColor: darkenColor(colors.fcrPrediksi3, 20),
                        borderWidth: 1,
                        barPercentage: 0.6
                    }
                ]
            };

            // Buat chart
            new Chart(ctx, {
                type: 'bar',
                data: chartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label && context.raw !== null) {
                                        label += ': ' + context.raw.toFixed(2);
                                        return label;
                                    }
                                    return null;
                                },
                                afterLabel: function(context) {
                                    const index = context.dataIndex;
                                    const datasetLabel = context.dataset.label;

                                    // Tooltip untuk prediksi
                                    if (datasetLabel.startsWith('FCR Prediksi')) {
                                        const predNumber = datasetLabel.split(' ')[2];
                                        let startDate, endDate, desc;

                                        if (predNumber === '1') {
                                            startDate = today;
                                            endDate = prediksi1End;
                                            desc = '40 hari pertama';
                                        } else if (predNumber === '2') {
                                            startDate = prediksi1End;
                                            endDate = prediksi2End;
                                            desc = '40 hari berikutnya';
                                        } else {
                                            startDate = prediksi2End;
                                            endDate = prediksi3End;
                                            desc = '40 hari terakhir';
                                        }

                                        return [
                                            `Periode: ${formatTanggal(startDate)} - ${formatTanggal(endDate)}`,
                                            `Rentang prediksi ${desc}`,
                                            `Rata-rata FCR: ${context.raw.toFixed(2)}`
                                        ].join('\n');
                                    }
                                    return null;
                                }
                            }
                        },
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    size: 12,
                                    weight: 'bold'
                                },
                                boxWidth: 12
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                autoSkip: false,
                                maxRotation: 0,
                                minRotation: 0,
                                padding : 10
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Nilai FCR',
                                font: {
                                    weight: 'bold',
                                    size: 14
                                }
                            },
                            min: 0,
                            max: Math.max(
                                2.0,
                                ...(historisData?.fcr || []),
                                avgPrediction1,
                                avgPrediction2,
                                avgPrediction3
                            ) * 1.1,
                            grid: {
                                drawOnChartArea: true
                            }
                        }
                    }
                }
            });

            // Fungsi helper
            function hexToRgba(hex, alpha) {
                const r = parseInt(hex.slice(1, 3), 16);
                const g = parseInt(hex.slice(3, 5), 16);
                const b = parseInt(hex.slice(5, 7), 16);
                return `rgba(${r}, ${g}, ${b}, ${alpha})`;
            }

            function darkenColor(color, percent) {
                const num = parseInt(color.replace('#', ''), 16);
                const amt = Math.round(2.55 * percent);
                const R = (num >> 16) - amt;
                const G = (num >> 8 & 0x00FF) - amt;
                const B = (num & 0x0000FF) - amt;
                return '#' + (
                    0x1000000 +
                    (R < 0 ? 0 : R) * 0x10000 +
                    (G < 0 ? 0 : G) * 0x100 +
                    (B < 0 ? 0 : B)
                ).toString(16).slice(1);
            }
        });
    </script>
    @endpush
