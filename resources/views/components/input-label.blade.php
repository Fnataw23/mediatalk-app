<label {{ $attributes->merge(['class' => 'block font-mono text-xs uppercase tracking-wider text-gray-300']) }}>
    {{ $value ?? $slot }}
</label>
