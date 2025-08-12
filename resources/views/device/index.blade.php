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
            <p class="text-sm italic text-slate-500">“Daftar Perangkat yang Dimiliki Pengguna”</p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="bg-white shadow-xl rounded-xl p-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Dropdown + Tombol Laporkan -->
                @if ($devices->count())
                    <div class="flex justify-end mb-6 items-center gap-3">
                        <div class="flex gap-3 items-center">
                            <div class="relative">
                                <select id="deviceSelect"
                                    class="appearance-none border border-gray-300 rounded-lg pl-4 pr-10 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition bg-white text-gray-700 hover:border-sky-400">
                                    <option value="">-- Pilih Device --</option>
                                    @foreach ($devices as $device)
                                        <option value="{{ route('devices.show', $device->id) }}">
                                            {{ $device->nama }} 
                                        </option>
                                    @endforeach
                                </select>
                                <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </span>
                            </div>
                            <button type="button" onclick="redirectToDevice()"
                                class="bg-gradient-to-r from-sky-700 to-sky-500 text-white px-5 py-2 rounded-lg shadow hover:from-sky-800 hover:to-sky-600 text-sm font-semibold transition">
                                Laporkan Perawatan
                            </button>
                        </div>
                    </div>

                    <!-- Modal Peringatan -->
                    <div id="warningModal" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
                        <div class="bg-white rounded-xl shadow-2xl max-w-sm w-full p-6 text-center">
                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">
                                <svg class="h-6 w-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856C18.403 19 19 18.403 19 17.656V6.344C19 5.597 18.403 5 17.656 5H6.344C5.597 5 5 5.597 5 6.344v11.312C5 18.403 5.597 19 6.344 19z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Perhatian</h3>
                            <p class="text-gray-600 text-sm mb-4">Silakan pilih perangkat terlebih dahulu sebelum melanjutkan.</p>
                            <button onclick="closeWarningModal()" 
                                class="px-4 py-2 bg-gradient-to-r from-sky-600 to-sky-400 text-white rounded-lg shadow hover:from-sky-700 hover:to-sky-500 transition text-sm">
                                Oke
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
                                document.getElementById('warningModal').classList.remove('hidden');
                                document.getElementById('warningModal').classList.add('flex');
                            }
                        }
                        function closeWarningModal() {
                            document.getElementById('warningModal').classList.add('hidden');
                            document.getElementById('warningModal').classList.remove('flex');
                        }
                    </script>
                @endif

                <!-- Tabel Device -->
                <div class="bg-white border border-slate-200 shadow-xl rounded-2xl overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm text-left">
                        <thead class ="bg-gradient-to-r from-sky-700 to-sky-500 text-white uppercase text-xs font-semibold text-left">
                            <tr>
                                <th class="px-6 py-4">ID</th>
                                <th class="px-6 py-4">Nama Device</th>
                                <th class="px-6 py-4">Spesifikasi</th>
                                <th class="px-6 py-4">Serah Terima</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-800 divide-y divide-slate-100">
                            @forelse ($devices as $device)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-semibold text-slate-800">{{ $device->id }}</td>
                                    <td class="px-6 py-4">{{ $device->nama }}</td>
                                    <td class="px-6 py-4 text-slate-600">{{ $device->spesifikasi }}</td>
                                    <td class="px-6 py-4 text-slate-600">
                                        {{ $device->tanggal_serah_terima ? \Carbon\Carbon::parse($device->tanggal_serah_terima)->translatedFormat('d-m-Y') : '-' }}
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
    </div>
</x-app-layout>
