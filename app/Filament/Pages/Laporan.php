<?php

namespace App\Filament\Pages;

use App\Models\User;
use App\Models\Maintenance;
use App\Models\TransaksiBarang;
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
            default => 'pdf.laporan-transaksi',
        };

        $pdf = Pdf::loadView($viewName, [
            'transaksi' => $this->jenis_laporan === 'transaksi' ? $data : null,
            'perawatan' => $this->jenis_laporan === 'perawatan' ? $data : null,
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
            default => 'pdf.laporan-transaksi',
        };

        $pdfOutput = Pdf::loadView($viewName, [
            'transaksi' => $this->jenis_laporan === 'transaksi' ? $data : null,
            'perawatan' => $this->jenis_laporan === 'perawatan' ? $data : null,
            'periode' => $this->getPeriodeLabel(),
            'user' => $user->name,
        ])->output();

        $user->notify(new LaporanNotification($user, $pdfOutput, $this->jenis_laporan));

        Notification::make()
            ->title('Laporan ' . ucfirst($this->jenis_laporan) . ' berhasil dikirim ke email user.')
            ->success()
            ->send();
    }

    private function getFilteredData()
    {
        $query = $this->jenis_laporan === 'transaksi'
            ? TransaksiBarang::query()
            : Maintenance::query();

        if (!empty($this->user_id)) {
            $query->where('user_id', $this->user_id);
        }

        if ($this->jenis_waktu === 'bulanan') {
            $query->whereYear('tanggal', $this->tahun)
                ->whereMonth('tanggal', $this->bulan);
        } else {
            $query->whereYear('tanggal', $this->tahun);
        }

        if ($this->jenis_laporan === 'transaksi') {
            return $query->with(['user'])->get();
        }

        return $query->with(['user', 'device'])->get();
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
