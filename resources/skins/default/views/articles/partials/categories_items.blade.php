@foreach($items as $item)
    <option value="{{ $item->id }}"
        @if ($item->alt_url)
            disabled style="text-decoration: line-through;"
        @elseif (! empty($article->id) and in_array($item->id, $article->categories->pluck('id')->all()))
            selected
        @elseif ($item->id === (int) request('category_id'))
            selected
        @endif
    >{{ str_repeat("–", $loop->depth - 1) }} {{ $item->title }}</option>

    @isset ($item->children)
        {{-- DO NOT use "@each(...)", because "$loop->..." does not work --}}
        @include('articles.partials.categories_items', ['items' => $item->children])
    @endisset
@endforeach
