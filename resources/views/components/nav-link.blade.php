@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-emerald-400 text-sm font-medium leading-5 text-emerald-400 focus:outline-none focus:border-emerald-300 transition duration-150 ease-in-out font-mono'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-emerald-500 hover:text-emerald-400 hover:border-emerald-600 focus:outline-none focus:text-emerald-400 focus:border-emerald-600 transition duration-150 ease-in-out font-mono';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
