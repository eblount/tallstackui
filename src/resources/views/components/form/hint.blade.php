@props(['hint' => null])

@php($personalize = tallstackui_personalization('form.hint', $personalization()))

<span @class($personalize['text'])>
    {{ $hint }}
</span>