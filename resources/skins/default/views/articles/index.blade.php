@extends('layouts.app')

@section('breadcrumb')
    @component('components.breadcrumbs')
        @slot('crumbs', [
            ['action' => 'articles.index', 'title' => 'articles'],
        ])
    @endcomponent
@endsection

@section('mainblock')
    	{{-- <!-- Filter form: BEGIN -->
        <form id="articles_filter" name="filters_criteria" action="{{ route('admin.articles.filter') }}" method="post" accept-charset="UTF-8" class="collapse">
            @csrf
            <div class="card ">
                <div class="card-header"><i class="fa fa-filter"></i> Фильтр элементов</div>
		        <div class="card-body">
                    <div class="form-group has-float-label">
                        <div class="input-group">
                            <label>Заголовок</label>
                            <input type="text" name="f[title]" value="{{ $f['title'] ?? '' }}" required class="form-control" />
                            <div class="input-group-append">
                                <button type="button" onclick="return confirm('shure') ? this.closest('.form-group').remove() : false;" class="btn btn-outline-primary">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group has-float-label">
                        <div class="input-group">
                            <label>Добавиь фильтр</label>
                            <select class="form-control">
                                @foreach ($filters as $key => $filter)
                                    <option value="{{ $filter }}">@lang(''.$filter)</option>
                                @endforeach
							</select>
                            <div class="input-group-append">
                                <button type="button" data-toggle="dropdown" class="btn btn-outline-primary dropdown-toggle">Критерий</button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="#contains" class="dropdown-item pt-1 pb-1">Contains</a>
                                    <a href="#its_equal" class="dropdown-item pt-1 pb-1">It's equal</a>
                                    <a href="#greather_than" class="dropdown-item pt-1 pb-1">Greather than &gt;</a>
                                    <a href="#less_than" class="dropdown-item pt-1 pb-1">Less than &lt; </a>
                                    <div role="separator" class="dropdown-divider"></div>
                                    <a href="#" class="dropdown-item pt-1 pb-1">Anything</a>
                                </div>
                            </div>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-primary">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group d-flex">
						<button type="submit" class="mr-auto btn btn-outline-primary">@lang('btn.apply')</button>

                        <button type="button" class="btn btn-outline-secondary" data-content="Используйте опреторы сравнения." onmouseover="$(this).popover();">
                            <i class="fa fa-question"></i>
                        </button>
					</div>
				</div>
    		</div>
        </form>
    	<!-- Filter form: END --> --}}

	<div class="card card-table">
        <div class="card-header d-flex d-print-none">
            @can ('admin.articles.create')
                <a href="{{ route('admin.articles.create') }}" class="btn btn-outline-dark"><i class="fa fa-plus"></i> @lang('btn.create')</a>
            @endcan
			<div class="btn-group d-flex ml-auto">
                @can ('admin.categories.index')
                    <a href="{{ route('admin.categories.index') }}" title="@lang('categories')" class="btn btn-outline-dark"><i class="fa fa-folder-open-o"></i></a>
                @endcan
                {{-- @can ('admin.xfields.modify')
                    <a href="route('admin.xfields.module', 'articles')" title="@lang('btn.add_xfield')" class="btn btn-outline-dark"><span class="as-icon">χφ</span></a>
                @endcan --}}
                @can ('admin.settings.details')
                    <a href="{{ route('admin.settings.module', 'articles') }}" title="@lang('settings')" class="btn btn-outline-dark"><i class="fa fa-cogs"></i></a>
                @endcan
			</div>
			<div class="btn-group ml-auto">
				{{-- <button type="button" title="@lang('btn.filter')" class="btn btn-outline-dark" data-toggle="collapse" data-target="#articles_filter"><i class="fa fa-filter"></i></button> --}}
				<button type="button" title="@lang('btn.print')" class="btn btn-outline-dark" onclick="window.print();"><i class="fa fa-print"></i></button>
			</div>
		</div>

        @empty ($articles->count())
            @lang('common.msg.not_found')
        @else
            <!-- Main content form -->
    		<form id="form_action" name="mass_update" action="{{ route('admin.articles.mass_update') }}" method="post" accept-charset="UTF-8" class="form-horizontal">
    			@csrf

                <!-- List of articles: BEGIN -->
    			<div class="card-body table-responsive">
    				<table class="table table-sm">
    					<thead>
    						<tr>
    							<th><input type="checkbox" class="select-all" title="@lang('select_all')" /></th>
    							<th>#</th>
    							<th class="hidden-xs"></th>
    							<th>@lang('title')</th>
    							<th class="hidden-xs"><i class="fa fa-comments"></i></th>
    							<th class="hidden-xs"><i class="fa fa-eye"></i></th>
    							<th class="hidden-xs">@lang('category')</th>
    							<th class="hidden-xs">@lang('author')</th>
    							<th class="hidden-xs">@lang('created_at')</th>
    							<th class="hidden-xs">@lang('updated_at')</th>
    							<th class="text-right d-print-none">@lang('action')</th>
    						</tr>
    					</thead>
    					<tbody>
    					@foreach($articles as $k => $article)
    						<tr>
    							<td class="{{ $errors->has('articles') ? ' has-error' : '' }}"><input type="checkbox" name="articles[]" value="{{ $article->id }}" /></td>
    							<td>{{ $article->id }}</td>
    							<td class="hidden-xs" nowrap>
                                    @if($article->state == 'published') <i class="fa fa-check text-success" title="@lang('state.published')"></i>
                                    @elseif($article->state == 'unpublished') <i class="fa fa-times text-danger" title="@lang('state.unpublished')"></i>
                                    @else <i class="fa fa-ban text-danger" title="@lang('state.draft')"></i>@endif
    								{{-- @if($article->files_count > 0)
    									<i class="fa fa-paperclip" title="@lang('articles.attach.count'): {{ $article->files_count }}"></i>
    								@endif
    								@if($article->images_count > 0)
    									<i class="fa fa-picture-o" title="@lang('articles.images.count'): {{ $article->images_count }}"></i>
    								@endif --}}
    							</td>
    							<td>
                                    <a href="{{ route('admin.articles.edit', $article) }}" class="">{{ $article->title }}</a>
    							</td>
								<td class="hidden-xs">{{ $article->comments_count }}</td>
    							<td class="hidden-xs">{{ $article->views }}</td>
    							<td class="hidden-xs">
                                    {{ wrap_attr(
                                        $article->categories,
                                        '<span class="cat-links"><a href="%url" target="_blank">%title</a></span>',
                                        ', '
                                        ) }}
                                </td>
    							<td class="hidden-xs">{{ $article->user->name }}</td>
    							<td class="hidden-xs">{{ $article->created_at }}</td>
    							<td class="hidden-xs">{{ $article->updated_at ?: __('not_updated') }}</td>
    							<td class="text-right d-print-none">
                                    <div class="btn-group">
                                        @can ('admin.articles.update', $article)
                                            <button type="submit" class="btn btn-link"
                                                formaction="{{ route('toggle.attribute', ['Article', $article->id, 'is_favorite']) }}"
                                                name="_method" value="PUT">
                                                <i class="fa {{ $article->is_favorite ? 'text-warning fa-star' : 'text-muted fa-star-o' }}"></i>
                                            </button>
                                            <button type="submit" class="btn btn-link"
                                                formaction="{{ route('toggle.attribute', ['Article', $article->id, 'is_catpinned']) }}"
                                                name="_method" value="PUT">
                                                <i class="fa fa-thumb-tack {{ $article->is_catpinned ? 'text-danger fa-rotate-90' : 'text-muted' }}"></i>
                                            </button>
                                            <button type="submit" class="btn btn-link"
                                                formaction="{{ route('toggle.attribute', ['Article', $article->id, 'on_mainpage']) }}"
                                                name="_method" value="PUT">
                                                <i class="fa fa-home {{ $article->on_mainpage ? 'text-success' : 'text-muted' }}"></i>
                                            </button>
                                        @endcan

                                        <a href="{{ $article->url }}" target="_blank" class="btn btn-link"><i class="fa fa-external-link"></i></a>

                                        @can ('admin.articles.update', $article)
                                            <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-link"><i class="fa fa-pencil"></i></a>
                                        @endcan

                                        @can ('admin.articles.delete', $article)
                                            <button type="submit" class="btn btn-link"
                                                onclick="return confirm('@lang('msg.sure')');"
                                                formaction="{{ route('admin.articles.delete', $article) }}"
                                                name="_method" value="DELETE">
                                                <i class="fa fa-trash-o text-danger"></i>
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-link text-muted" disabled><i class="fa fa-trash-o"></i></button>
                                        @endcan
                                    </div>
                                </td>
    						</tr>
                        @endforeach
    					</tbody>
    				</table>
    			</div>
    			<div class="card-footer">
    				<div class="row">
    					<div class="col col-md-4">
    						<div class="input-group {{ $errors->has('mass_action') ? ' has-error' : '' }} d-print-none">
    							<select name="mass_action" class="form-control">
                                    {{-- <optgroup label="Публикация">
                                        <option value="published">@lang('Publish')</option>
                                        <option value="unpublished">@lang('Send to moderation')</option>
                                    </optgroup> --}}
    								<option value="" class="divider">-- @lang('action') --</option>
    								<option value="published">@lang('action.published')</option>
    								<option value="unpublished">@lang('action.unpublished')</option>
    								<option value="draft">@lang('action.draft')</option>

    								<option value="" class="divider" disabled>===================</option>
    								<option value="on_mainpage">@lang('action.on_mainpage')</option>
    								<option value="not_on_mainpage">@lang('action.not_on_mainpage')</option>

    								<option value="" class="divider" disabled>===================</option>
    								<option value="allow_com">@lang('action.allow_com')</option>
    								<option value="disallow_com">@lang('action.disallow_com')</option>

    								<option value="" class="divider" disabled>===================</option>
    								<option value="currdate">@lang('action.currdate')</option>

    								<option value="" class="divider" disabled>===================</option>
    								<option value="delete">@lang('action.delete')</option>
									<option value="delete_drafts">@lang('action.delete_drafts')</option>
    							</select>
    							<div class="input-group-append">
    								<button type="submit" class="btn btn-outline-dark">@lang('btn.apply')</button>
    							</div>
    						</div>
    					</div>
    					<div class="col col-md-8 text-right">
    						{{ $articles->links('components.pagination') }}
    					</div>
    				</div>
    			</div>
    		</form>
        @endempty
	</div>
@endsection

@push('scripts')
    <script>
    // Open/close the collapse panels based on history
    var storage  = localStorage;

    $('.collapse').on('hidden.bs.collapse', function () {
        storage.removeItem('open_' + this.id);
    });

    $('.collapse').on('shown.bs.collapse', function () {
        storage.setItem('open_' + this.id, true);
    });

    $('.collapse').each(function () {
        // Default close unless saved as open
        if (storage.getItem('open_' + this.id)) {
            $(this).collapse('show');
        }
    });
    </script>
    {{-- <!-- List of vendor: BEGIN -->
    <script src="{{ skin_asset('js/libsuggest.js') }}"></script>
    <!-- List of vendor: END -->

    <!-- Hidden SUGGEST div -->
    <div id="suggestWindow" class="suggestWindow">
        <table id="suggestBlock" class="table"></table>
        <a href="#" id="suggestClose">@lang('btn.close')</a>
    </div>

    <script>
        $(function() {
            var aSuggest = new ngSuggest('an', {
                'localPrefix'	: $localPrefix,
                'reqMethodName'	: 'core.users.search',
                'lId' : 'suggestLoader',
                'hlr' : 'true',
                'stCols' : 2,
                'stColsClass': [ 'cleft', 'cright' ],
                'stColsHLR'	: [ true, false ],
            });
        });
    </script> --}}
@endpush
