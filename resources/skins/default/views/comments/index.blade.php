@extends('layouts.app')

@section('breadcrumb')
    @component('components.breadcrumbs')
        @slot('crumbs', [
            ['action' => 'comments.index', 'title' => 'comments'],
        ])
    @endcomponent
@endsection

@section('mainblock')
	<div class="card card-default card-table">
		<div class="card-header d-flex d-print-none">
            @can ('admin.comments.create')
                <a href="{{ route('admin.comments.create') }}" class="btn btn-outline-dark"><i class="fa fa-plus"></i>  @lang('btn.create')</a>
            @endcan
            <div class="btn-group d-flex ml-auto">
                @can ('admin.settings.details')
                    <a href="{{ route('admin.settings.module', 'comments') }}" title="@lang('settings')" class="btn btn-outline-dark"><i class="fa fa-cogs"></i></a>
                @endcan
			</div>
            <div class="btn-group ml-auto">
				<button type="button" title="@lang('btn.print')" class="btn btn-outline-dark" onclick="window.print();"><i class="fa fa-print"></i></button>
			</div>
		</div>

        @empty ($comments->count())
            @lang('common.msg.not_found')
        @else
            <!-- Main content form -->
    		<form name="mass_update" action="{{ route('admin.comments.mass_update') }}" method="post" accept-charset="UTF-8" class="form-horizontal">
    			@csrf

                <!-- List of comments: BEGIN -->
                <div class="card-body table-responsive">
    				<table class="table table-sm">
                        <thead>
                            <tr>
                                <th class="{{ $errors->has('comments') ? ' has-error' : '' }}">
                                    <input type="checkbox" class="select-all" title="@lang('select_all')" />
                                </th>
                                <th>#</th>
                                <th>@lang('content_head')</th>
                                <th>@lang('created_at')</th>
                                <th class="text-right d-print-none">@lang('action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($comments as $key => $comment)
        						<tr>
                                    <td><input type="checkbox" name="comments[]" value="{{ $comment->id }}" /></td>
                                    <td>{{ $comment->id }}</td>
        							<td>
                                        @if ($comment->by_user)<a href="{{ $comment->user->profile }}">@endif
                                            {{ $comment->author->name }}
                                        @if ($comment->by_user)</a>@endif
                                        : {{ teaser($comment->content, 100) }}
                                    </td>
        							<td style="white-space: nowrap;">{{ $comment->created }}</td>
        							<td class="text-right">
                            			<div class="btn-group">
                                            @can ('admin.comments.update', $comment)
                                                @if (setting('comments.moderate'))
                                                    <button type="submit" class="btn btn-link"
                                                        formaction="{{ route('toggle.attribute', ['Comment', $comment->id, 'is_approved']) }}"
                                                        name="_method" value="PUT">
                                                        <i class="fa {{ $comment->is_approved ? 'fa-eye text-success' : 'fa-eye-slash text-danger' }}"></i>
                                                    </button>
                                                @endif
                                            @endcan
                                            <a href="{{ $comment->url }}" target="_blank" class="btn btn-link"><i class="fa fa-external-link"></i></a>
                                            @can ('admin.comments.update', $comment)
                                                <a href="{{ route('admin.comments.edit', $comment) }}"
                                                    class="btn btn-link"
                                                    data-action="{{ route('admin.comments.update', $comment) }}"
                                                    {{-- data-toggle="modal"
                                                    data-target="#exampleModal" --}}
                                                    >
                                                    <i class="fa fa-pencil"></i></a>
                                            @endcan
                                            @can ('admin.comments.delete', $comment)
                                                <button type="submit" class="btn btn-link text-danger"
                                                    onclick="return confirm('@lang('msg.sure')');"
                                                    formaction="{{ route('admin.comments.delete', $comment) }}"
                                                    name="_method" value="DELETE"
                                                    ><i class="fa fa-trash-o"></i></button>
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
                                    <option value="" class="divider">-- @lang('action') --</option>
    								<option value="published">@lang('action.published')</option>
    								<option value="unpublished">@lang('action.unpublished')</option>
    								<option value="" class="divider" disabled>===================</option>
    								<option value="delete">@lang('action.delete')</option>
    							</select>
    							<div class="input-group-append">
    								<button type="submit" class="btn btn-outline-dark">@lang('btn.apply')</button>
    							</div>
    						</div>
    					</div>
    					<div class="col col-md-8 text-right">
    						{{ $comments->links('components.pagination') }}
    					</div>
    				</div>
    			</div>
    		</form>
        @endif
	</div>

    <div id="exampleModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('editing')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form action="" method="post" class="modal-body">
                    <input type="hidden" name="_method" value="PUT" />
                    @csrf
                    <div class="form-group">
                        <textarea name="content" rows="8" class="form-control"></textarea>
                    </div>

                    <hr>

                    <div class="d-flex">
                        <button type="submit" class="btn btn-primary">@lang('btn.save')</button>
                        <button type="button" class="btn btn-secondary ml-auto" data-dismiss="modal">@lang('btn.close')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('#exampleModal').on('show.bs.modal', async function (event) {
            let modal = $(this)
            let button = $(event.relatedTarget)
            try {
                modal.find('form').attr('action', button.data('action'))
                const response = await axios.get(button.attr('href'))
                modal.find('.modal-body textarea').val(response.data.content)
            } catch (error) {
                modal.find('.modal-body').html('<div class="alert alert-danger">'+error.response.data+'</div>')
            }
        });
        $('#exampleModal').on('shown.bs.modal', function (event) {
            $(this).find('.modal-body textarea').focus()
        });
    </script>
@endpush
