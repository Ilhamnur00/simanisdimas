<x-filament::card>
    <h2 class="text-lg font-bold mb-4">Grafik Transaksi Barang per Bulan</h2>

<!-- Info Total Barang -->
<div class="mb-4 text-gray-700">
    <span class="inline-block px-3 py-1 text-sm font-medium bg-green-100 text-green-700 rounded-full shadow-sm">
        Total Barang Terdaftar: {{ $totalBarang }}
    </span>
</div>


    <!-- Filter Tahun Transaksi Barang -->
    <form method="GET" class="grid grid-cols-1 sm:grid-cols-[auto,1fr] items-center gap-3 mb-6 max-w-xs">
        <label for="tahun_barang" class="text-base font-medium whitespace-nowrap">Pilih Tahun:</label>
        <select name="tahun_barang" id="tahun_barang" onchange="this.form.submit()"
            class="border border-gray-300 rounded-md p-2 text-sm w-full focus:ring-2 focus:ring-blue-500 focus:outline-none shadow-sm">
            @foreach ($availableYearsBarang as $year)
                <option value="{{ $year }}" {{ $year == $selectedYearBarang ? 'selected' : '' }}>
                    {{ $year }}
                </option>
            @endforeach
        </select>

        <!-- Pertahankan tahun_device agar tidak reset -->
        <input type="hidden" name="tahun_device" value="{{ $selectedYearDevice }}">
    </form>

    <!-- Canvas Grafik -->
    <canvas id="barangChart" height="120"></canvas>
</x-filament::card>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('barangChart').getContext('2d');

        const gradientBar = ctx.createLinearGradient(0, 0, 0, 400);
        gradientBar.addColorStop(0, 'rgba(255, 99, 132, 0.9)');
        gradientBar.addColorStop(1, 'rgba(255, 99, 132, 0.3)');

        const gradientLine = ctx.createLinearGradient(0, 0, 0, 400);
        gradientLine.addColorStop(0, 'rgba(255, 99, 132, 1)');
        gradientLine.addColorStop(1, 'rgba(255, 99, 132, 0.1)');

        const glowingPlugin = {
            id: 'glow-barang',
            beforeDraw: chart => {
                const ctx = chart.ctx;
                chart.data.datasets.forEach((dataset, i) => {
                    const meta = chart.getDatasetMeta(i);
                    if (!meta.hidden && dataset.type === 'line') {
                        ctx.save();
                        ctx.shadowColor = 'rgba(255, 99, 132, 0.6)';
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
                        label: 'Jumlah Transaksi Barang',
                        data: @json($barangChartData),
                        backgroundColor: gradientBar,
                        borderRadius: 8,
                        barPercentage: 0.6,
                        categoryPercentage: 0.5,
                        borderSkipped: false,
                        hoverBackgroundColor: 'rgba(255, 99, 132, 1)',
                        hoverBorderColor: '#b3003c',
                    },
                    {
                        type: 'line',
                        label: 'Garis Tren',
                        data: @json($barangChartData),
                        fill: false,
                        borderColor: gradientLine,
                        borderWidth: 3,
                        pointBackgroundColor: '#ff6384',
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
                                return `Jumlah Transaksi: ${tooltipItem.raw}`;
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
