@props([
    'additional' => null,
    'dismissible' => false,
    'heading' => null,
    'message' => null,
    'type' => 'danger',
])

@php
    if ('error' === $type) {
        $type = 'danger';
    }

    $attributes = $attributes->class([
        'alert alert-'.$type,
        'alert-dismissible fade show' => $dismissible,
    ])->merge([
        'role' => 'alert',
    ]);
@endphp

<div {{ $attributes }}>
    @if ($dismissible)
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    @endif

    @if ($heading)
    <h5 class="alert-heading">{{ $heading }}</h5>
    @endif

    {{ $message ?: $slot }}

    @if ($additional)
    <hr>
    <p class="mb-0">{{ $additional }}</p>
    @endif
</div>
