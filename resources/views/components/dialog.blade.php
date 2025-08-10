@props(['id'])

<dialog id="{{ $id }}" {{ $attributes->merge(['class' => 'modal']) }}>
    <div class="modal-box">
        @if (isset($title))
        <h3 class="text-lg font-bold">{{ $title }}</h3>
        @endif

        {{ $slot }}

        <div class="modal-action">
            {{ $actions ?? '' }}
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>