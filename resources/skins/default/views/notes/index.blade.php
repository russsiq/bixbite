@extends('layouts.app')

@section('breadcrumb')
    @component('components.breadcrumbs')
        @slot('crumbs', [
            ['action' => 'notes.index', 'title' => 'notes'],
        ])
    @endcomponent
@endsection

@section('mainblock')
	<div class="card card-default card-table">
		<div class="card-header d-flex d-print-none">
            @can ('admin.notes.create')
                <a href="{{ route('admin.notes.create') }}" class="btn btn-outline-dark"><i class="fa fa-plus"></i>  @lang('btn.create')</a>
            @endcan
			<div class="btn-group ml-auto">
				{{-- <button type="button" title="@lang('btn.filter')" class="btn btn-outline-dark" data-toggle="collapse" data-target="#notes_filter"><i class="fa fa-filter"></i></button> --}}
				<button type="button" title="@lang('btn.print')" class="btn btn-outline-dark" onclick="window.print();"><i class="fa fa-print"></i></button>
			</div>
		</div>

        @empty ($notes->count())
            @lang('common.msg.not_found')
        @else
            <!-- Main content form -->
    		<form id="form_action" name="mass_update" action="" method="post" accept-charset="UTF-8" class="form-horizontal">
    			@csrf

                <!-- List of notes: BEGIN -->
                <div class="card-body table-responsive">
    				<table class="table table-sm">
                        <tbody>
                            @foreach ($notes as $key => $note)
        						<tr>
        							<td>
                                        <a href="{{ route('admin.notes.edit', $note) }}" class="">{{ $note->title }}</a>
                                        <br>
                                        {!! $note->description !!}
                                    </td>
        							<td>{{ $note->created }}</td>
        							<td class="text-right">
                            			<div class="btn-group">
                                            @if (! $note->is_completed)
                                                @can ('admin.notes.update', $note)
                                    				<button type="submit" class="btn btn-link text-warning"
                                                        formaction="{{ route('toggle.attribute', ['Note', $note->id, 'is_completed']) }}"
                                                        name="_method" value="PUT"><i class="fa fa-line-chart"></i></button>
                                                    <a href="{{ route('admin.notes.edit', $note) }}" class="btn btn-link"><i class="fa fa-pencil"></i></a>
                                                @endcan
                                				<button type="button" class="btn btn-link text-muted" disabled><i class="fa fa-trash-o"></i></button>
                                            @else
                                                @can ('admin.notes.update', $note)
                                    				<button type="submit" class="btn btn-link text-success"
                                                        formaction="{{ route('toggle.attribute', ['Note', $note->id, 'is_completed']) }}"
                                                        name="_method" value="PUT"><i class="fa fa-check"></i></button>
                                                    <a href="{{ route('admin.notes.edit', $note) }}" class="btn btn-link"><i class="fa fa-pencil"></i></a>
                                                @endcan
                                				<button type="submit" class="btn btn-link text-danger"
                                                    onclick="return confirm('@lang('msg.sure')');"
                                                    formaction="{{ route('admin.notes.delete', $note) }}"
                                                    name="_method" value="DELETE"
                                                    ><i class="fa fa-trash-o"></i></button>
                                            @endif
                            			</div>
                                    </td>
        						</tr>
                            @endforeach
    					</tbody>
    				</table>
    			</div>

                @if ($notes->hasPages())
        			<div class="card-footer">
        				<div class="row">
        					<div class="col col-md-8 ofset-md-4 text-right">
        						{{ $notes->links('components.pagination') }}
        					</div>
        				</div>
        			</div>
                @endif
    		</form>
        @endif
	</div>
@endsection

@push('scripts')
    <!-- List of vendor: BEGIN -->
    <script src="{{ skin_asset('js/libsuggest.js') }}"></script>
    <!-- List of vendor: END -->

    <script>
        $(function() {
            var aSuggest = new ngSuggest('name', {
                'localPrefix': '',
                'reqMethodName': 'core.notes.search',
                'lId': 'suggestLoader',
                'hlr': 'true',
                'stCols': 2,
                'stColsClass': [ 'cleft', 'cright' ],
                'stColsHLR': [ true, false ],
            });
        });
    </script>
@endpush
