<x-filament::card>
    <h2 class="text-lg font-bold mb-4">Grafik Perawatan Device per Bulan</h2>

<!-- Info Total Device -->
<div class="mb-4 text-gray-700">
    <span class="inline-block px-3 py-1 text-sm font-medium bg-blue-100 text-blue-700 rounded-full shadow-sm">
        Total Device Terdaftar: {{ $totalDevice }}
    </span>
</div>


    <!-- Filter Tahun Perawatan Device -->
    <form method="GET" class="grid grid-cols-1 sm:grid-cols-[auto,1fr] items-center gap-3 mb-6 max-w-xs">
        <label for="tahun_device" class="text-base font-medium whitespace-nowrap">Pilih Tahun:</label>
        <select name="tahun_device" id="tahun_device" onchange="this.form.submit()"
            class="border border-gray-300 rounded-md p-2 text-sm w-full focus:ring-2 focus:ring-blue-500 focus:outline-none shadow-sm">
            @foreach ($availableYearsDevice as $year)
                <option value="{{ $year }}" {{ $year == $selectedYearDevice ? 'selected' : '' }}>
                    {{ $year }}
                </option>
            @endforeach
        </select>

        <!-- Pertahankan tahun_barang agar grafik barang tidak reset -->
        <input type="hidden" name="tahun_barang" value="{{ $selectedYearBarang }}">
    </form>

    <!-- Canvas Grafik -->
    <canvas id="deviceChart" height="120"></canvas>
</x-filament::card>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('deviceChart').getContext('2d');

            const gradientBar = ctx.createLinearGradient(0, 0, 0, 400);
            gradientBar.addColorStop(0, 'rgba(0, 123, 255, 0.9)');
            gradientBar.addColorStop(1, 'rgba(0, 123, 255, 0.3)');

            const gradientLine = ctx.createLinearGradient(0, 0, 0, 400);
            gradientLine.addColorStop(0, 'rgba(0, 123, 255, 1)');
            gradientLine.addColorStop(1, 'rgba(0, 123, 255, 0.1)');

            const glowingPlugin = {
                id: 'glow',
                beforeDraw: chart => {
                    const ctx = chart.ctx;
                    chart.data.datasets.forEach((dataset, i) => {
                        const meta = chart.getDatasetMeta(i);
                        if (!meta.hidden && dataset.type === 'line') {
                            ctx.save();
                            ctx.shadowColor = 'rgba(0, 123, 255, 0.6)';
                            ctx.shadowBlur = 20;
                            ctx.shadowOffsetX = 0;
                            ctx.shadowOffsetY = 0;
                            ctx.stroke();
                            ctx.restore();
                        }
                    });
                }
            };

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [
                        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                    ],
                    datasets: [
                        {
                            type: 'bar',
                            label: 'Jumlah Perawatan',
                            data: @json($deviceChartData),
                            backgroundColor: gradientBar,
                            borderRadius: 8,
                            barPercentage: 0.6,
                            categoryPercentage: 0.5,
                            borderSkipped: false,
                            hoverBackgroundColor: 'rgba(0, 123, 255, 1)',
                            hoverBorderColor: '#0056b3',
                        },
                        {
                            type: 'line',
                            label: 'Garis Kufra',
                            data: @json($deviceChartData),
                            fill: false,
                            borderColor: gradientLine,
                            borderWidth: 3,
                            pointBackgroundColor: '#007bff',
                            tension: 0.4,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            labels: {
                                color: '#333',
                                font: {
                                    weight: 'bold'
                                }
                            }
                        },
                        tooltip: {
                            enabled: true,
                            backgroundColor: '#222',
                            titleColor: '#fff',
                            bodyColor: '#eee',
                            padding: 10,
                            cornerRadius: 8,
                            titleFont: { weight: 'bold', size: 14 },
                            bodyFont: { size: 13 },
                            displayColors: false,
                            callbacks: {
                                label: (tooltipItem) => {
                                    return `Jumlah Perawatan: ${tooltipItem.raw}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                color: '#555'
                            },
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            }
                        },
                        x: {
                            ticks: {
                                color: '#555'
                            },
                            grid: {
                                display: false
                            }
                        }
                    },
                    animation: {
                        duration: 1200,
                        easing: 'easeOutExpo'
                    }
                },
                plugins: [glowingPlugin]
            });
        });
    </script>
@endpush
