<x-filament::page>
    <!-- Grafik dan Filter Transaksi Barang -->
    <div class="mt-8">
        @include('components.grafik-transaksi-barang', [
            'barangChartData' => $barangChartData,
            'selectedYearBarang' => $selectedYearBarang,
            'availableYearsBarang' => $availableYearsBarang,
            'selectedYearDevice' => $selectedYearDevice,
            'totalBarang' => $totalBarang, // <-- Tambahan ini
        ])
    </div>
    
    <!-- Grafik dan Filter Perawatan Device -->
    <div class="mt-8">
        @include('components.grafik-perawatan-device', [
            'deviceChartData' => $deviceChartData,
            'selectedYearDevice' => $selectedYearDevice,
            'availableYearsDevice' => $availableYearsDevice,
            'selectedYearBarang' => $selectedYearBarang,
            'totalDevice' => $totalDevice, // <--- Tambahkan ini
        ])
    </div>

    <div class="mt-8">
    @include('components.grafik-total-kendaraan', [
    'kendaraanChartData' => $kendaraanChartData,
    'selectedYearKendaraan' => $selectedYearKendaraan,
    'availableYearsKendaraan' => $availableYearsKendaraan,
    'totalKendaraan' => $totalKendaraan,
    ])

    </div>
</x-filament::page>
