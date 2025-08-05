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

    public ?string $user_id = ''; // string kosong untuk 'semua pengguna'
    public string $jenis_laporan = 'bulanan';
    public ?string $bulan = null;
    public ?string $tahun = null;

    public function mount(): void
    {
        $this->form->fill([
            'user_id' => '',
            'jenis_laporan' => 'bulanan',
            'bulan' => now()->month,
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
                ->description('Gunakan filter ini untuk menghasilkan laporan transaksi yang sesuai.')
                ->schema([
                    Select::make('user_id')
                        ->label('Pilih Pengguna')
                        ->options(['' => 'Semua Pengguna'] + User::all()->pluck('name', 'id')->toArray())
                        ->searchable()
                        ->default('')
                        ->helperText('Kosongkan untuk mencetak laporan seluruh pengguna.')
                        ->columnSpanFull(),

                    Select::make('jenis_laporan')
                        ->label('Jenis Laporan')
                        ->options([
                            'bulanan' => 'Bulanan',
                            'tahunan' => 'Tahunan',
                        ])
                        ->reactive()
                        ->default('bulanan')
                        ->columnSpanFull(),

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
                        ->requiredIf('jenis_laporan', 'bulanan')
                        ->default((string) now()->month)
                        ->columnSpan([
                            'md' => 6,
                        ]),

                    Select::make('tahun')
                        ->label('Tahun')
                        ->options(
                            collect(range(now()->year, now()->year - 5))
                                ->mapWithKeys(fn ($y) => [$y => $y])
                                ->toArray()
                        )
                        ->default((string) now()->year)
                        ->required()
                        ->columnSpan([
                            'md' => 6,
                        ]),
                ])
                ->columns([
                    'md' => 12,
                ])
                ->collapsible()
                ->collapsed(false),
        ];
    }

    public function downloadPdf(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $data = $this->getFilteredData();
        $userName = $this->user_id
            ? (User::find($this->user_id)?->name ?? '-')
            : 'Semua Pengguna';

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
        if (empty($this->user_id)) {
            Notification::make()
                ->title('Silakan pilih pengguna untuk mengirim email laporan.')
                ->danger()
                ->send();
            return;
        }

        $data = $this->getFilteredData();
        $user = User::findOrFail($this->user_id);

        $transaksi = collect($this->utf8ize($data->toArray()))
                        ->map(fn ($item) => (object) $item);

        $pdfOutput = Pdf::loadView('pdf.laporan-transaksi', [
            'transaksi' => $transaksi,
            'periode' => $this->getPeriodeLabel(),
            'user' => $user->name,
        ])->output();

        $user->notify(new LaporanNotification($user, $pdfOutput));

        Notification::make()
            ->title('Laporan berhasil dikirim ke email user.')
            ->success()
            ->send();
    }

    private function getFilteredData()
    {
        $query = TransaksiBarang::query();

        if (!empty($this->user_id)) {
            $query->where('user_id', $this->user_id);
        }

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
