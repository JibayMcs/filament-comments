<x-filament::section
    collapsible
    collapsed
    persist-collapsed
    id="comments-section"
>

    <x-slot name="heading">
        {{__('filament-comments::filament-comments.modal.heading')}}
    </x-slot>

    <livewire:comments
        :record="$record"
        :guard="$guard"
        :notifyFrom="$notifyFrom"
        :notifyTo="$notifyTo"
    />
</x-filament::section>
