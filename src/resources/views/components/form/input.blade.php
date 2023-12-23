@php
    [$property, $error, $id] = $bind($attributes, $errors ?? null, isset($__livewire));
    $personalize = $classes();
@endphp

<x-wrapper.input :$id :$property :$error :$label :$hint :$invalidate>
    @if ($icon)
        <div @class([ $personalize['icon.wrapper'], $personalize['icon.paddings.' . $position]])>
            <x-icon :$icon :$error @class([$personalize['icon.size'], $personalize['icon.color'] => !$invalidate]) />
        </div>
    @endif
    <div @class([
            $personalize['input.class.wrapper'],
            $personalize['input.class.color.base'] => !$error,
            $personalize['input.class.color.background'] => !$attributes->get('disabled') && !$attributes->get('readonly'),
            $personalize['input.class.color.disabled'] => $attributes->get('disabled') || $attributes->get('readonly'),
            $personalize['input.paddings.left'] => $icon && ($position === null || $position === 'left'),
            $personalize['input.paddings.right'] => $icon && $position === 'right',
            $personalize['error'] => $error
        ])>
        @if ($prefix)
            <span @class([$personalize['input.class.slot'], $personalize['error'] => $error])>{{ $prefix }}</span>
        @endif
        <input @if ($id) id="{{ $id }}" @endif {{ $attributes->class($personalize['input.class.base']) }}>
        @if ($suffix)
            <span @class([$personalize['input.class.slot'], $personalize['error'] => $error])>{{ $suffix }}</span>
        @endif
    </div>
</x-wrapper.input>
