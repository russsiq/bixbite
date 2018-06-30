@extends('layouts.app')

@section('breadcrumb')
    @component('components.breadcrumbs')
        @slot('crumbs', [
            ['action' => 'categories.index', 'title' => 'categories'],
        ])
    @endcomponent
@endsection

@section('mainblock')
    <div class="card">
        <div class="card-header d-flex d-print-none">
            @can ('admin.categories.create')
                <a href="{{ route('admin.categories.create') }}" class="btn btn-outline-dark"><i class="fa fa-plus"></i> @lang('btn.create')</a>
            @endcan
            <div class="btn-group d-flex ml-auto">
                @can ('admin.settings.details')
                    <a href="{{ route('admin.settings.module', 'categories') }}" title="@lang('settings')" class="btn btn-outline-dark"><i class="fa fa-cogs"></i></a>
                @endcan
			</div>
            <div class="btn-group ml-auto">
                {{-- <button type="button" title="@lang('btn.filter')" class="btn btn-outline-dark" data-toggle="collapse" data-target="#categories_filter"><i class="fa fa-filter"></i></button> --}}
                <button type="button" title="@lang('btn.print')" class="btn btn-outline-dark" onclick="window.print();"><i class="fa fa-print"></i></button>
            </div>
        </div>
        
        @empty ($categories->count())
            <div class="card-body">@lang('common.msg.not_found')</div>
        @else
            <!-- Main content form -->
            <form id="form_action" name="mass_update" action="{{ route('admin.categories.position_update') }}" method="post" accept-charset="UTF-8" class="form-horizontal">
                @csrf

                <!-- List of categories: BEGIN -->
                <div class="card-body">
                    <div id="nestable" class="dd nestable-with-handle">
                        <ol class="dd-list">
                            @each('categories.partials.index_categories', $categories, 'item')
                        </ol>
                    </div>
    			</div>
                <div class="card-footer">
                    <a id="position_update" href="#" class="btn btn-outline-success">@lang('btn.save')</a>
                    <a id="position_reset"
                        href="{{ route('admin.categories.position_reset') }}"
                        onclick="return confirm('@lang('msg.sure')');"
                        class="btn btn-outline-warning">@lang('btn.reset')</a>
                </div>
            </form>
        @endempty
    </div>
@endsection

@push('scripts')
    <!-- List of vendor: BEGIN -->
    <link href=" {{ skin_asset('css/jquery-nestable.css') }}" rel="stylesheet" />
@endpush

@push('scripts')
    <!-- List of vendor: BEGIN -->
    <script src="{{ skin_asset('js/jquery.nestable.js') }}"></script>
    <!-- List of vendor: END -->

    <script>
        $(function() {
            // To sort category
            $('#nestable').nestable();
            // To change position category
            $(document).on('click', '#position_update', function(e) {
                e.preventDefault();
                $.reqJSON($(this).closest('form').attr('action'), {list: $('#nestable').nestable('serialize')}, function(json) {
                    $.notify({message: json.message}, {type: 'success'});
                });
            });
        });
    </script>
@endpush
