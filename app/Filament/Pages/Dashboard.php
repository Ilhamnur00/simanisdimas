<?php

namespace App\Filament\Pages;

use App\Models\LaporanPerawatan;
use App\Models\Maintenance;
use App\Models\TransaksiBarang;
use App\Models\Device;
use App\Models\Barang;
use App\Models\Kendaraan;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.dashboard';

    public array $barangChartData = [];
    public array $deviceChartData = [];
    public array $kendaraanChartData = [];

    public int $totalBarang;
    public int $totalDevice;
    public int $totalKendaraan;

    public int $selectedYearBarang;
    public int $selectedYearDevice;
    public int $selectedYearKendaraan;

    public array $availableYearsBarang = [];
    public array $availableYearsDevice = [];
    public array $availableYearsKendaraan = [];

    public function mount(): void
    {
        $this->selectedYearBarang = request('tahun_barang', now()->year);
        $this->selectedYearDevice = request('tahun_device', now()->year);
        $this->selectedYearKendaraan = request('tahun_kendaraan', now()->year);

        $this->availableYearsBarang = TransaksiBarang::selectRaw('YEAR(tanggal) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->toArray();

        $this->availableYearsDevice = Maintenance::selectRaw('YEAR(tanggal) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->toArray();

        $this->availableYearsKendaraan = Kendaraan::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->toArray();

        $this->barangChartData = $this->generateMonthlyData(TransaksiBarang::class, 'tanggal', $this->selectedYearBarang);
        $this->deviceChartData = $this->generateMonthlyData(Maintenance::class, 'tanggal', $this->selectedYearDevice);
        $this->kendaraanChartData = $this->generateMonthlyData(LaporanPerawatan::class, 'tanggal', $this->selectedYearKendaraan);

        $this->totalBarang = Barang::count();
        $this->totalDevice = Device::count();
        $this->totalKendaraan = Kendaraan::count();
    }

    private function generateMonthlyData(string $model, string $dateColumn, int $year): array
    {
        $monthly = $model::selectRaw("MONTH($dateColumn) as month, COUNT(*) as count")
            ->whereYear($dateColumn, $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $data = array_fill(1, 12, 0); // Bulan 1-12
        foreach ($monthly as $row) {
            $data[$row->month] = $row->count;
        }

        return array_values($data); // urutan sesuai bulan
    }
}
