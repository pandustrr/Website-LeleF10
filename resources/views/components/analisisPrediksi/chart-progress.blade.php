<div class="w-full" style="height: 500px;">
    <canvas id="progressChart"></canvas>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('progressChart').getContext('2d');
        const historisData = @json($historisData);

        // Warna untuk chart
        const colors = {
            pakan: '#F59E0B', // amber-500
            panen: '#10B981', // emerald-500
            fcr: '#3B82F6', // blue-500
            fcrLight: '#93C5FD', // blue-300
            standarBaik: '#059669', // emerald-600
            standarSedang: '#D97706' // amber-600
        };

        // Siapkan data untuk tooltip
        const tooltipData = historisData.data_details.map(detail => {
            return {
                siklus: detail.siklus_name,
                pakan: detail.pakan,
                panen: detail.panen,
                fcr: detail.fcr.toFixed(2),
                periode: `${detail.tanggal_mulai} - ${detail.tanggal_akhir}`
            };
        });

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: historisData.labels,
                datasets: [
                    {
                        label: 'Total Pakan (kg)',
                        data: historisData.pakan,
                        backgroundColor: hexToRgba(colors.pakan, 0.7),
                        borderColor: colors.pakan,
                        borderWidth: 1,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Total Panen (kg)',
                        data: historisData.panen,
                        backgroundColor: hexToRgba(colors.panen, 0.7),
                        borderColor: colors.panen,
                        borderWidth: 1,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Nilai FCR',
                        data: historisData.fcr,
                        backgroundColor: hexToRgba(colors.fcr, 0.7),
                        borderColor: colors.fcr,
                        borderWidth: 1,
                        yAxisID: 'y1',
                        // Atur lebar batang FCR lebih kecil
                        barPercentage: 0.3,
                        categoryPercentage: 0.5
                    },
                    {
                        label: 'Standar Baik (≤1.0)',
                        data: Array(historisData.labels.length).fill(1.0),
                        type: 'line',
                        borderColor: colors.standarBaik,
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        pointRadius: 0,
                        yAxisID: 'y1'
                    },
                    {
                        label: 'Batas Maksimal (1.5)',
                        data: Array(historisData.labels.length).fill(1.5),
                        type: 'line',
                        borderColor: colors.standarSedang,
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        pointRadius: 0,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += context.parsed.y.toFixed(2);
                                    if (context.dataset.label.includes('FCR')) {
                                        label += ' (Pakan/Panen)';
                                    } else {
                                        label += ' kg';
                                    }
                                }
                                return label;
                            },
                            afterLabel: function(context) {
                                const dataIndex = context.dataIndex;
                                const tooltip = tooltipData[dataIndex];
                                return [
                                    `Siklus: ${tooltip.siklus}`,
                                    `Periode: ${tooltip.periode}`,
                                    `Pakan: ${tooltip.pakan} kg`,
                                    `Panen: ${tooltip.panen} kg`,
                                    `FCR: ${tooltip.fcr}`
                                ].join('\n');
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
                        stacked: false,
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        stacked: false,
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Pakan & Panen (kg)',
                            color: colors.pakan,
                            font: {
                                weight: 'bold',
                                size: 14
                            }
                        },
                        min: 0,
                        grid: {
                            drawOnChartArea: true
                        }
                    },
                    y1: {
                        stacked: false,
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Nilai FCR',
                            color: colors.fcr,
                            font: {
                                weight: 'bold',
                                size: 14
                            }
                        },
                        min: 0,
                        max: Math.max(3, ...historisData.fcr) + 0.5,
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                }
            }
        });

        function hexToRgba(hex, alpha) {
            const r = parseInt(hex.slice(1, 3), 16);
            const g = parseInt(hex.slice(3, 5), 16);
            const b = parseInt(hex.slice(5, 7), 16);
            return `rgba(${r}, ${g}, ${b}, ${alpha})`;
        }
    });
</script>
@endpush
