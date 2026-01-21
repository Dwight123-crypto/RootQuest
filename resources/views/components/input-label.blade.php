@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-emerald-400 font-mono']) }}>
    {{ $value ?? $slot }}
</label>
