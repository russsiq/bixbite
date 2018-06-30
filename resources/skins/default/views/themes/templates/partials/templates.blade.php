@foreach($items as $item => $value)
    <li class="tree-view__item">
        @if (is_array($value))
            <a href="#collapse-{{ $temp_id = $item.'-'.rand() }}" class="tree-view__link" data-toggle="collapse"><i class="fa fa-folder-o text-muted"></i> @lang('_'.$item)</a>
            <ul id="collapse-{{ $temp_id }}" class="tree-view__subitem collapse">
                @include('themes.templates.partials.templates', ['items' => $value])
            </ul>
        @else
            <a href="#" class="tree-view__link" data-path="{{ $item }}"><i class="fa fa-file-text-o text-muted"></i> @lang($value)</a>
        @endif
    </li>
@endforeach
