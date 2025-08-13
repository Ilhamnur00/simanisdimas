{{-- resources/views/barang/request.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="space-y-1">
            <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-[#003973] via-[#2980B9] to-[#6DD5FA] drop-shadow">
                Inventaris Barang
            </h2>
            <p class="text-slate-700 text-base">Sistem Manajemen Inventaris Dinas Komunikasi dan Informatika Kabupaten Banyumas</p>
            <p class="text-sm italic text-slate-500">“Transaksi Barang Inventaris oleh Pengguna”</p>
        </div>
    </x-slot>

    {{-- Tom Select CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">

    <style>
        .ts-wrapper {
            width: 100%;
        }
        .ts-control {
            border: 1px solid #d1d5db;
            border-radius: 0.75rem;
            padding: 0.5rem 0.75rem;
            min-height: 42px;
            display: flex;
            align-items: center;  
        }
        .ts-control input {
            line-height: 1.4;
        }
        .ts-dropdown {
            border-radius: 0.75rem;
            border: 1px solid #d1d5db;
        }
    </style>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('barang.request.store') }}" method="POST"
                class="bg-white/80 border border-slate-200 shadow-2xl backdrop-blur-sm rounded-2xl px-10 pt-8 pb-10 space-y-8">
                @csrf

                <input type="hidden" name="jenis_transaksi" value="keluar">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    {{-- Barang --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-1">Barang</label>
                        <select id="barang_id" name="barang_id" required>
                            <option value=""></option>
                            @foreach ($barang as $b)
                                <option 
                                    value="{{ $b->id }}" 
                                    data-kategori="{{ $b->kategori_id }}"
                                    data-stok="{{ $b->stok }}"
                                    {{ old('barang_id') == $b->id ? 'selected' : '' }}>
                                    {{ $b->nama_barang }}
                                </option>
                            @endforeach
                        </select>
                        <div class="text-sm text-gray-600 mt-2" id="stok_info">
                            Stok tersedia: <span id="stok_tersedia">-</span>
                        </div>
                    </div>

                    {{-- Jumlah --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-1">Jumlah</label>
                        <input type="number" name="jumlah_barang" id="jumlah"
                            class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                            required min="1" value="{{ old('jumlah_barang', 1) }}">
                    </div>

                    {{-- Tanggal --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-1">Tanggal</label>
                        <input type="date" name="tanggal"
                            class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                            value="{{ old('tanggal', \Carbon\Carbon::now()->format('Y-m-d')) }}" required>
                    </div>
                </div>

                {{-- Tombol --}}
                <div class="text-right pt-6">
                    <button type="submit"
                        class="bg-gradient-to-r from-sky-700 to-sky-500 text-white px-6 py-2 rounded-md shadow-md hover:from-sky-800 hover:to-sky-600 transition text-sm">
                        Konfirmasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- JS Library --}}
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- SweetAlert Notifikasi --}}
    @if (session('success'))
        <script>
            Swal.fire({ icon: 'success', title: 'Berhasil', text: '{{ session('success') }}', confirmButtonColor: '#3085d6' });
        </script>
    @endif
    @if (session('error'))
        <script>
            Swal.fire({ icon: 'error', title: 'Gagal', text: '{{ session('error') }}', confirmButtonColor: '#d33' });
        </script>
    @endif
    @if ($errors->any())
        <script>
            Swal.fire({ icon: 'error', title: 'Validasi Gagal', html: `{!! implode('<br>', $errors->all()) !!}` });
        </script>
    @endif

    {{-- Script Barang & Stok --}}
    <script>
        const stokSpan = document.getElementById('stok_tersedia');
        const jumlahInput = document.getElementById('jumlah');

        function tampilkanStokFromOption(opt) {
            if (!opt || !opt.dataset.stok) {
                stokSpan.textContent = '-';
                jumlahInput.max = '';
                return;
            }
            stokSpan.textContent = `${opt.dataset.stok} unit`;
            jumlahInput.max = opt.dataset.stok;
        }

        jumlahInput.addEventListener('input', () => {
            const stokMaks = parseInt(jumlahInput.max);
            if (stokMaks && parseInt(jumlahInput.value) > stokMaks) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Jumlah Melebihi Stok',
                    text: `Stok tersedia hanya ${stokMaks} unit`,
                    confirmButtonColor: '#3085d6',
                });
                jumlahInput.value = stokMaks;
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            const ts = new TomSelect('#barang_id', {
                create: false,
                placeholder: "-- Pilih barang --",
                allowEmptyOption: true,
                maxOptions: 100,
                maxItems: 1,
                render: {
                    option: function(data, escape) {
                        const stok = data.$option?.dataset?.stok ? data.$option.dataset.stok + ' unit' : '';
                        return `<div>${escape(data.text)} <span style="float:right;opacity:.6">${escape(stok)}</span></div>`;
                    }
                },
                onChange: function(value) {
                    const opt = document.querySelector(`#barang_id option[value="${value}"]`);
                    tampilkanStokFromOption(opt);
                }
            });

            // Set stok awal jika ada nilai default (old input)
            const initialValue = document.getElementById('barang_id').value;
            if (initialValue) {
                const opt = document.querySelector(`#barang_id option[value="${initialValue}"]`);
                tampilkanStokFromOption(opt);
            }
        });
    </script>
</x-app-layout>
