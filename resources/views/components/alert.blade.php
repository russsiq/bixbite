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
        'alert-dismissible fade show' => $dismissible,
    ])->merge([
        'class' => 'alert alert-'.$type,
        'role' => 'alert',
    ]);
@endphp

<div {{ $attributes }}>
    @if ($dismissible)
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    @endif

    @if ($heading)
    <h4 class="alert-heading">{{ $heading }}</h4>
    @endif

    {{ $message ?: $slot }}

    @if ($additional)
    <hr>
    <p class="mb-0">{{ $additional }}</p>
    @endif
</div>
