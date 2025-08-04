<?php

namespace App\Filament\Pages;

use App\Models\User;
use App\Models\TransaksiBarang;
use App\Notifications\LaporanNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Forms\Form;
use Illuminate\Support\Carbon;

class LaporanTransaksi extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-down';
    protected static string $view = 'filament.pages.laporan-transaksi';
    protected static ?string $navigationLabel = 'Laporan Transaksi';
    protected static ?string $title = 'Laporan Transaksi';

    public ?int $user_id = null;
    public string $jenis_laporan = 'bulanan';
    public ?string $bulan = null;
    public ?string $tahun = null;

    public function mount(): void
    {
        $this->form->fill([
            'jenis_laporan' => 'bulanan',
            'tahun' => now()->year,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form->schema($this->getFormSchema());
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make('Filter Laporan')
                ->schema([
                    Select::make('user_id')
                        ->label('Pilih User')
                        ->options(User::all()->pluck('name', 'id')->toArray())
                        ->searchable()
                        ->required()
                        ->reactive(),

                    Radio::make('jenis_laporan')
                        ->label('Jenis Laporan')
                        ->options([
                            'bulanan' => 'Bulanan',
                            'tahunan' => 'Tahunan',
                        ])
                        ->inline()
                        ->reactive(),

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
                        ->visible(fn ($get) => $get('jenis_laporan') === 'bulanan')
                        ->requiredIf('jenis_laporan', 'bulanan'),

                    Select::make('tahun')
                        ->label('Tahun')
                        ->options(
                            collect(range(now()->year, now()->year - 5))->mapWithKeys(fn ($y) => [$y => $y])->toArray()
                        )
                        ->required(),
                ]),
        ];
    }

    public function downloadPdf(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $data = $this->getFilteredData();
        $userName = User::find($this->user_id)?->name ?? '-';

        $pdf = Pdf::loadView('pdf.laporan-transaksi', [
            'transaksi' => $this->utf8ize($data->toArray()),
            'periode' => $this->getPeriodeLabel(),
            'user' => $userName,
        ]);

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'laporan-transaksi.pdf'
        );
    }
    public function kirimEmail(): void
    {
        // Ambil data hasil filter
        $data = $this->getFilteredData();

        // Ambil user berdasarkan ID
        $user = User::findOrFail($this->user_id);

        // Konversi ke array of object agar bisa dipakai di view pakai ->tanggal
        $transaksi = collect($this->utf8ize($data->toArray()))
                        ->map(fn ($item) => (object) $item);

        // Generate PDF
        $pdfOutput = Pdf::loadView('pdf.laporan-transaksi', [
            'transaksi' => $transaksi,
            'periode' => $this->getPeriodeLabel(),
            'user' => $user->name,
        ])->output();

        // Kirim notifikasi email
        $user->notify(new LaporanNotification($user, $pdfOutput));

        // Tampilkan notifikasi sukses
        Notification::make()
            ->title('Laporan berhasil dikirim ke email user.')
            ->success()
            ->send();
    }


    private function getFilteredData()
    {
        $query = TransaksiBarang::query()->where('user_id', $this->user_id);

        if ($this->jenis_laporan === 'bulanan') {
            $query->whereYear('tanggal', $this->tahun)
                  ->whereMonth('tanggal', $this->bulan);
        } else {
            $query->whereYear('tanggal', $this->tahun);
        }

        return $query->get();
    }

    private function getPeriodeLabel(): string
    {
        if ($this->jenis_laporan === 'bulanan') {
            $bulanNama = Carbon::create()->month($this->bulan)->translatedFormat('F');
            return "$bulanNama $this->tahun";
        }

        return "Tahun $this->tahun";
    }

    private function utf8ize($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->utf8ize($value);
            }
        } elseif (is_string($data)) {
            return mb_convert_encoding($data, 'UTF-8', 'UTF-8');
        }

        return $data;
    }
}
