@can ('admin.articles.create')
    @if(pageinfo('is_category'))
        <a href="{{ route('admin.articles.create', ['category_id' => pageinfo('category')->id]) }}" class="pull-right fa fa-plus"></a>
    @endif
@endcan

<header class="page-header"><h2 class="section-title">{{ pageinfo('title') }}</h2></header>

@if(pageinfo('is_category') and pageinfo('category')->info)
    <p>{{ pageinfo('category')->info }}</p>
@endif

<ul>
    @forelse ($articles as $article)
        <li><a href="{{ $article->url }}">{{ $article->title }}</a></li>
    @empty
        @lang('common.msg.not_found')
    @endforelse
</ul>

<div class="general-pagination group">{{ $articles->links('components.pagination') }}</div>
