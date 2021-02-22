@props(['items', 'count', 'title' => 'title', 'description' => 'description'])

<section id="{{ $id = 'accordion-'.md5($items) }}" class="accordion">
    @foreach ($items as $item)
    <div class="accordion-item">
        <h2 id="{{ $heading = 'heading-'.$id.'-'.$loop->iteration }}" class="accordion-header">
            <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button"
                data-bs-toggle="collapse" data-bs-target="#{{ $target = 'target-'.$id.'-'.$loop->iteration }}"
                aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="{{ $target }}">
                {{ $item->{$title} }}
            </button>
        </h2>
        <div id="{{ $target }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
            aria-labelledby="{{ $heading }}" data-bs-parent="#{{ $id }}">
            <div class="accordion-body">
                {{ $item->{$description} }}
            </div>
        </div>
    </div>
    @endforeach
</section>
