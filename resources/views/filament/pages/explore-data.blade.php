<x-filament-panels::page>
    {{ $this->form }}
    <div class="flex justify-end">
        {{-- @if(!empty($this->datas['file']))
            <x-filament::button wire:click="create" class="w-auto">
                Submit
            </x-filament::button>
        @endif --}}
    </div>
    @if(!empty($this->datas["indikator_id"]))
        <h1 class="text-8xl font-bold text-center">{{$this->judul}}</h1>
        <div>
            {{$this->table}}
        </div>
    @endif
</x-filament-panels::page>