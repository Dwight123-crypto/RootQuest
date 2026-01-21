@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-emerald-700 bg-terminal-bg text-emerald-400 focus:border-emerald-500 focus:ring-emerald-500 focus:ring-offset-terminal-bg rounded-md shadow-sm font-mono placeholder-emerald-700']) }}>
