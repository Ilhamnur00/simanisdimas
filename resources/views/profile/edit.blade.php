<x-app-layout>
    <x-slot name="header">
        <div class="mb-6">
            <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-sky-900 via-indigo-900 to-slate-800">
                Profil
            </h2>
            <p class="text-sm text-slate-600 mt-1">
                Halo <span class="font-semibold">{{ Auth::user()->name }}</span>, kelola informasi akun dan pengaturan Anda di sini untuk pengalaman penggunaan yang lebih optimal.
            </p>
        </div>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            
        </div>
    </div>
</x-app-layout>
