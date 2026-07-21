@props(['value' => null, 'className' => ''])

<label {{ $attributes->merge(['class' => 'block font-bold text-sm text-text-primary mb-1.5 ' . $className]) }}>
    {{ $value ?? $slot }}
</label>
