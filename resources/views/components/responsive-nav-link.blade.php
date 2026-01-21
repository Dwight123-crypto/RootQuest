@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-emerald-400 text-start text-base font-medium text-emerald-400 bg-emerald-900/30 focus:outline-none focus:text-emerald-300 focus:bg-emerald-900/50 focus:border-emerald-300 transition duration-150 ease-in-out font-mono'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-emerald-500 hover:text-emerald-400 hover:bg-emerald-900/20 hover:border-emerald-600 focus:outline-none focus:text-emerald-400 focus:bg-emerald-900/20 focus:border-emerald-600 transition duration-150 ease-in-out font-mono';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
