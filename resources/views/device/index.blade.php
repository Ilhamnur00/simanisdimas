<x-app-layout>
    <x-slot name="title">
        Manajemen Device
    </x-slot>

    <x-slot name="header">
        <div class="space-y-1">
            <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-[#003973] via-[#2980B9] to-[#6DD5FA] drop-shadow">
                Manajemen Perangkat Elektronik
            </h2>
            <p class="text-slate-700 text-base">Aplikasi Inventaris Dinas Komunikasi dan Informatika</p>
            <p class="text-sm italic text-slate-500">“Laporan Perawatan Perangkat oleh Pengguna”</p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Dropdown + Tombol Laporkan -->
            @if ($devices->count())
                <div class="flex justify-end mb-6 items-center gap-2">
                    <div class="flex gap-2">
                        <select id="deviceSelect"
                            class="border border-gray-300 rounded-md px-4 py-2 text-sm shadow-sm focus:ring focus:ring-indigo-200">
                            <option value="">-- Pilih Device --</option>
                            @foreach ($devices as $device)
                                <option value="{{ route('devices.show', $device->id) }}">
                                    {{ $device->nama }} 
                                </option>
                            @endforeach
                        </select>
                        <button type="button" onclick="redirectToDevice()"
                            class="bg-gradient-to-r from-sky-700 to-sky-500 text-white px-4 py-2 rounded-lg shadow hover:from-sky-800 hover:to-sky-600 text-sm font-semibold transition">
                            Laporkan Perawatan
                        </button>
                    </div>
                </div>

                <script>
                    function redirectToDevice() {
                        const select = document.getElementById('deviceSelect');
                        const url = select.value;
                        if (url) {
                            window.location.href = url;
                        } else {
                            alert('Silakan pilih device terlebih dahulu.');
                        }
                    }
                </script>
            @endif

            <!-- Tabel Device -->
            <div class="bg-white border border-slate-200 shadow-xl rounded-2xl overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm text-left">
                    <thead class ="bg-gradient-to-r from-sky-700 to-sky-500 text-white uppercase text-xs font-semibold text-left">
                        <tr>
                            <th class="px-6 py-4">ID Device</th>
                            <th class="px-6 py-4">Nama User</th>
                            <th class="px-6 py-4">Nama Device</th>
                            <th class="px-6 py-4">Spesifikasi</th>
                            <th class="px-6 py-4">Tanggal Serah Terima</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800 divide-y divide-slate-100">
                        @forelse ($devices as $device)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-semibold text-slate-800">{{ $device->id }}</td>
                                <td class="px-6 py-4">{{ $device->user->name ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $device->nama }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $device->spesifikasi }}</td>
                                <td class="px-6 py-4 text-slate-600">
                                    {{ $device->tanggal_serah_terima ? \Carbon\Carbon::parse($device->tanggal_serah_terima)->translatedFormat('d F Y') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-6 text-center text-slate-500 italic">
                                    Tidak ada data perangkat yang terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
