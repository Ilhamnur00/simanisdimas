<?php

namespace App\Filament\Pages;

use App\Models\User;
use App\Models\Maintenance;
use App\Models\TransaksiBarang;
use App\Models\LaporanPerawatan;
use App\Models\LaporanPajak;
use App\Notifications\LaporanNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Carbon;

class Laporan extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-down';
    protected static ?string $navigationLabel = 'Laporan';
    protected static ?string $title = 'Laporan';
    protected static string $view = 'filament.pages.laporan';

    public string $jenis_laporan = 'transaksi';
    public ?string $user_id = '';
    public string $jenis_waktu = 'bulanan';
    public ?string $bulan = null;
    public ?string $tahun = null;

    public function mount(): void
    {
        $this->form->fill([
            'jenis_laporan' => 'transaksi',
            'user_id' => '',
            'jenis_waktu' => 'bulanan',
            'bulan' => now()->month,
            'tahun' => now()->year,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Pilih Jenis Laporan')
                ->schema([
                    Select::make('jenis_laporan')
                        ->label('Jenis Laporan')
                        ->options([
                            'transaksi' => 'Laporan Transaksi',
                            'perawatan' => 'Laporan Perawatan Device',
                            'perawatan_kendaraan' => 'Laporan Perawatan Kendaraan',
                            'pajak_kendaraan' => 'Laporan Pajak Kendaraan',
                        ])
                        ->reactive(),
                ]),

            Section::make('Filter Laporan')
                ->schema([
                    Select::make('user_id')
                        ->label('Pilih Pengguna')
                        ->options(['' => 'Semua Pengguna'] + User::all()->pluck('name', 'id')->toArray())
                        ->searchable()
                        ->default('')
                        ->helperText('Kosongkan untuk semua pengguna.'),

                    Select::make('jenis_waktu')
                        ->label('Jenis Waktu')
                        ->options([
                            'bulanan' => 'Bulanan',
                            'tahunan' => 'Tahunan',
                        ])
                        ->reactive()
                        ->default('bulanan'),

                    Select::make('bulan')
                        ->label('Bulan')
                        ->options([
                            '1' => 'Januari',
                            '2' => 'Februari',
                            '3' => 'Maret',
                            '4' => 'April',
                            '5' => 'Mei',
                            '6' => 'Juni',
                            '7' => 'Juli',
                            '8' => 'Agustus',
                            '9' => 'September',
                            '10' => 'Oktober',
                            '11' => 'November',
                            '12' => 'Desember',
                        ])
                        ->visible(fn ($get) => $get('jenis_waktu') === 'bulanan')
                        ->default((string) now()->month)
                        ->requiredIf('jenis_waktu', 'bulanan'),

                    Select::make('tahun')
                        ->label('Tahun')
                        ->options(
                            collect(range(now()->year, now()->year - 5))
                                ->mapWithKeys(fn ($y) => [$y => $y])
                                ->toArray()
                        )
                        ->default((string) now()->year)
                        ->required(),
                ])
        ]);
    }

    public function downloadPdf()
    {
        $data = $this->getFilteredData();
        $userName = $this->user_id ? (User::find($this->user_id)?->name ?? '-') : 'Semua Pengguna';

        $viewName = match ($this->jenis_laporan) {
            'perawatan' => 'pdf.laporan-perawatan-device',
            'perawatan_kendaraan' => 'pdf.laporan-perawatan-kendaraan',
            'pajak_kendaraan' => 'pdf.laporan-pajak-kendaraan',
            default => 'pdf.laporan-transaksi',
        };

        $pdf = Pdf::loadView($viewName, [
            'transaksi' => $this->jenis_laporan === 'transaksi' ? $data : null,
            'perawatan' => $this->jenis_laporan === 'perawatan' ? $data : null,
            'perawatan_kendaraan' => $this->jenis_laporan === 'perawatan_kendaraan' ? $data : null,
            'pajak_kendaraan' => $this->jenis_laporan === 'pajak_kendaraan' ? $data : null,
            'periode' => $this->getPeriodeLabel(),
            'user' => $userName,
        ]);

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'laporan-' . $this->jenis_laporan . '.pdf'
        );
    }

    public function kirimEmail()
    {
        if (empty($this->user_id)) {
            Notification::make()
                ->title('Silakan pilih pengguna untuk mengirim email laporan.')
                ->danger()
                ->send();
            return;
        }

        $data = $this->getFilteredData();
        $user = User::findOrFail($this->user_id);

        $viewName = match ($this->jenis_laporan) {
            'perawatan' => 'pdf.laporan-perawatan-device',
            'perawatan_kendaraan' => 'pdf.laporan-perawatan-kendaraan',
            'pajak_kendaraan' => 'pdf.laporan-pajak-kendaraan',
            default => 'pdf.laporan-transaksi',
        };

        $pdfOutput = Pdf::loadView($viewName, [
            'transaksi' => $this->jenis_laporan === 'transaksi' ? $data : null,
            'perawatan' => $this->jenis_laporan === 'perawatan' ? $data : null,
            'perawatan_kendaraan' => $this->jenis_laporan === 'perawatan_kendaraan' ? $data : null,
            'pajak_kendaraan' => $this->jenis_laporan === 'pajak_kendaraan' ? $data : null,
            'periode' => $this->getPeriodeLabel(),
            'user' => $user->name,
        ])->output();

        $user->notify(new LaporanNotification($user, $pdfOutput, $this->jenis_laporan));

        Notification::make()
            ->title('Laporan ' . ucfirst(str_replace('_', ' ', $this->jenis_laporan)) . ' berhasil dikirim ke email user.')
            ->success()
            ->send();
    }

    private function getFilteredData()
    {
        $query = match ($this->jenis_laporan) {
            'transaksi' => TransaksiBarang::query(),
            'perawatan' => Maintenance::query(),
            'perawatan_kendaraan' => LaporanPerawatan::query(),
            'pajak_kendaraan' => LaporanPajak::query(),
            default => null,
        };

        if (!$query) return collect();

        // Filtering berdasarkan user ID, tergantung jenis laporan
        if (!empty($this->user_id)) {
            $query = match ($this->jenis_laporan) {
                'transaksi', 'perawatan' => $query->where('user_id', $this->user_id),
                'perawatan_kendaraan', 'pajak_kendaraan' => $query->whereHas('kendaraan', fn ($q) =>
                    $q->where('user_id', $this->user_id)
                ),
                default => $query,
            };
        }

        // Filter berdasarkan waktu
        if ($this->jenis_waktu === 'bulanan') {
            $query->whereYear('tanggal', $this->tahun)
                ->whereMonth('tanggal', $this->bulan);
        } else {
            $query->whereYear('tanggal', $this->tahun);
        }

        // Eager load relasi sesuai jenis laporan
        return match ($this->jenis_laporan) {
            'transaksi' => $query->with(['user'])->get(),
            'perawatan' => $query->with(['user', 'device'])->get(),
            'perawatan_kendaraan', 'pajak_kendaraan' => $query->with(['kendaraan.user'])->get(),
            default => $query->get(),
        };
    }


    private function getPeriodeLabel(): string
    {
        Carbon::setLocale('id');

        if ($this->jenis_waktu === 'bulanan') {
            $bulanNama = Carbon::create()->month((int) $this->bulan)->translatedFormat('F');
            return "$bulanNama $this->tahun";
        }

        return "Tahun $this->tahun";
    }
}
