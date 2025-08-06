<x-filament::page>
    <div class="space-y-6">
        {{ $this->form }}

        <div class="flex justify-end gap-4">
            <x-filament::button
                color="primary"
                wire:click="downloadPdf"
                icon="heroicon-o-document-arrow-down"
            >
                Download PDF
            </x-filament::button>

            <x-filament::button
                color="success"
                wire:click="kirimEmail"
                icon="heroicon-o-envelope"
            >
                Kirim Email
                </x-filament::button>
        </div>
    </div>
</x-filament::page>
