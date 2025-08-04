<x-filament::page>
    <div class="flex justify-end space-x-2 mb-4">
        <x-filament::button wire:click="downloadPdf" color="primary">
            Download PDF
        </x-filament::button>
        <x-filament::button wire:click="kirimEmail" color="success">
            Kirim Email
        </x-filament::button>
    </div>
    {{ $this->form }}
</x-filament::page>
