<x-filament::card>
    <h2 class="text-lg font-bold mb-4">Grafik Total Kendaraan per Bulan</h2>

    <!-- Info Total Kendaraan -->
    <div class="mb-4 text-gray-700">
        <span class="inline-block px-3 py-1 text-sm font-medium bg-green-100 text-green-700 rounded-full shadow-sm">
            Total Kendaraan Terdaftar: {{ $totalKendaraan }}
        </span>
    </div>

    <!-- Filter Tahun -->
    <form method="GET" class="grid grid-cols-1 sm:grid-cols-[auto,1fr] items-center gap-3 mb-6 max-w-xs">
        <label for="tahun_kendaraan" class="text-base font-medium whitespace-nowrap">Pilih Tahun:</label>
        <select name="tahun_kendaraan" id="tahun_kendaraan" onchange="this.form.submit()"
            class="border border-gray-300 rounded-md p-2 text-sm w-full focus:ring-2 focus:ring-green-500 focus:outline-none shadow-sm">
            @foreach ($availableYearsKendaraan as $year)
                <option value="{{ $year }}" {{ $year == $selectedYearKendaraan ? 'selected' : '' }}>
                    {{ $year }}
                </option>
            @endforeach
        </select>

        <!-- Pertahankan filter lain -->
        <input type="hidden" name="tahun_barang" value="{{ $selectedYearBarang }}">
        <input type="hidden" name="tahun_device" value="{{ $selectedYearDevice }}">
    </form>

    <!-- Canvas Grafik -->
    <canvas id="kendaraanChart" height="120"></canvas>
</x-filament::card>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('kendaraanChart').getContext('2d');

        // Gradasi Hijau untuk bar
        const gradientBar = ctx.createLinearGradient(0, 0, 0, 400);
        gradientBar.addColorStop(0, 'rgba(16, 185, 129, 0.9)');   // #10B981
        gradientBar.addColorStop(1, 'rgba(167, 243, 208, 0.3)'); // #A7F3D0

        // Gradasi hijau terang untuk garis tren
        const gradientLine = ctx.createLinearGradient(0, 0, 0, 400);
        gradientLine.addColorStop(0, 'rgba(5, 150, 105, 1)');   // #059669
        gradientLine.addColorStop(1, 'rgba(5, 150, 105, 0.1)');

        const glowingPlugin = {
            id: 'glow-kendaraan',
            beforeDraw: chart => {
                const ctx = chart.ctx;
                chart.data.datasets.forEach((dataset, i) => {
                    const meta = chart.getDatasetMeta(i);
                    if (!meta.hidden && dataset.type === 'line') {
                        ctx.save();
                        ctx.shadowColor = 'rgba(5, 150, 105, 0.5)';
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
                        label: 'Jumlah Kendaraan Masuk',
                        data: @json($kendaraanChartData),
                        backgroundColor: gradientBar,
                        borderRadius: 8,
                        barPercentage: 0.6,
                        categoryPercentage: 0.5,
                        borderSkipped: false,
                        hoverBackgroundColor: 'rgba(5, 150, 105, 1)',
                        hoverBorderColor: '#064E3B',
                    },
                    {
                        type: 'line',
                        label: 'Garis Tren',
                        data: @json($kendaraanChartData),
                        fill: false,
                        borderColor: gradientLine,
                        borderWidth: 3,
                        pointBackgroundColor: '#10B981',
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
                                return `Jumlah Kendaraan: ${tooltipItem.raw}`;
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
