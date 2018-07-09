<li class="dd-item dd3-item" data-id="{{ $item->id }}">
    <div class="dd-handle dd3-handle btn"># {{ $item->id }}</div>
    <div class="dd3-content">

        <a href="{{ route('admin.categories.edit', $item) }}">{{ $item->title }}</a>

        <small>[id: {{ $item->id }}, {{ $item->articles_count ?: __('No') }} {{ trans_choice('articles.num', $item->articles_count) }}]</small>

        <div class="btn-group pull-right">
            @if($item->info)
                <button type="button" title="{{ $item->info }}" class="btn btn-link text-dark" readonly>
                    <i class="fa fa-info"></i>
                </button>
            @endif
            @if($item->image)
                <button type="button" title="" class="btn btn-link text-dark" readonly>
                    <i class="fa fa-file-image-o"></i>
                </button>
            @endif
            @can ('admin.categories.update', $item)
                <button type="submit" class="btn btn-link"
                    formaction="{{ route('toggle.attribute', ['Category', $item->id, 'show_in_menu']) }}"
                    name="_method" value="PUT">
                    <i class="fa {{ $item->show_in_menu ? 'fa-eye text-success' : 'fa-eye-slash text-danger' }}"></i>
                </button>
            @endcan

            <a href="{{ $item->url }}" target="_blank" class="btn btn-link"><i class="fa fa-external-link"></i></a>

            @can ('admin.categories.update', $item)
                <a href="{{ route('admin.categories.edit', $item) }}" class="btn btn-link"><i class="fa fa-pencil"></i></a>
            @endcan
            @can ('admin.categories.delete', $item)
                <button type="submit" class="btn btn-link"
                    onclick="return confirm('@lang('msg.sure')');"
                    formaction="{{ route('admin.categories.delete', $item) }}"
                    name="_method" value="DELETE">
                    <i class="fa fa-trash-o text-danger"></i>
                </button>
            @endcan
        </div>
    </div>

    @isset($item->children)
        <ol class="dd-list">
            @each('categories.partials.index_categories', $item->children, 'item')
        </ol><!-- .children -->
    @endisset
</li>
