@extends('layouts.app')

@section('breadcrumb')
    @component('components.breadcrumbs')
        @slot('crumbs', [
            ['action' => 'users.index', 'title' => 'users'],
        ])
    @endcomponent
@endsection

@section('mainblock')
    <!-- Info content -->
    <div class="page_main">
    	<div class="card card-table">
    		<div class="card-header d-flex d-print-none">
                @can ('admin.users.create')
                    <a href="{{ route('admin.users.create') }}" class="btn btn-outline-dark"><i class="fa fa-plus"></i>  @lang('btn.add')</a>
                @endcan
    			<div class="btn-group d-flex ml-auto">
                    @can ('privileges')
                        <a href="{{ route('admin.privileges.index') }}" title="@lang('privileges')" class="btn btn-outline-dark"><i class="fa fa-user-secret"></i></a>
                    @endcan
                    @can ('x_fields')
                        <a href="{{ route('admin.x_fields.index') }}" title="@lang('x_fields')" class="btn btn-outline-dark"><span class="as-icon">χφ</span></a>
                    @endcan
                    @can ('admin.settings.details')
                        <a href="{{ route('admin.settings.module', 'users') }}" title="@lang('settings')" class="btn btn-outline-dark"><i class="fa fa-cogs"></i></a>
                    @endcan
    			</div>
    			<div class="btn-group ml-auto"><!-- NOT PRINT - is private information --></div>
    		</div>

            <!-- Main content form -->
    		<form id="form_action" name="mass_update" action="{{ route('admin.users.mass_update') }}" method="post" accept-charset="UTF-8" class="form-horizontal">
    			@csrf

                <!-- List of users: BEGIN -->
                <div class="card-body table-responsive">
    				<table class="table table-sm">
    					<thead>
    						<tr>
    							{{-- <th><input type="checkbox" class="select-all" title="@lang('select_all')" /></th> --}}
    							<th>#</th>
    							<th>@lang('name')</th>
    							<th class="d-print-none"><i class="fa fa-newspaper-o"></i></th>
    							<th class="d-print-none"><i class="fa fa-comments-o"></i></th>
    							<th>@lang('role')</th>
    							<th class="d-print-none">@lang('email')</th>
    							<th class="hidden-xs">@lang('registered_at')</th>
    							<th class="hidden-xs">@lang('logined_at')</th>
    							<th>@lang('action')</th>
    						</tr>
    					</thead>
    					<tbody>
    					@foreach($users as $k => $user)
    						<tr>
    							{{-- <td class="{{ $errors->has('users') ? ' has-error' : '' }}"><input name="users[]" value="{{ $user->id }}" type="checkbox" /></td> --}}
    							<td>{{ $user->id }}</td>
    							<td>
                                    <b class="{{ $user->isOnline() ? 'text-success' : '' }}">&nbsp;•&nbsp;</b>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="">{{ $user->name }}</a>
                                </td>
    							<td class="d-print-none">{{ $user->articles_count }}</td>
    							<td class="d-print-none">{{ $user->comments_count }}</td>
    							<td>@lang($user->role)</td>
    							<td class="d-print-none">{{ $user->email }}</td>
    							<td class="hidden-xs">{{ $user->created_at }}</td>
    							<td class="hidden-xs">{{ $user->logined or __('never_been') }} {{ $user->last_ip ?? '' }}</td>
                                <td class="text-right">
                                    <div class="btn-group">
                                        @if (! $user->banned)
                                            <button type="submit" title="@lang('btn.banned')" class="btn btn-link text-dark"
                                                formaction="{{ route('admin.users.update', $user) }}"
                                                name="_method" value="PUT"><i class="fa fa-ban"></i></button>
                                        @else
                                            <button type="submit" title="@lang('btn.banned')" class="btn btn-link text-danger"
                                                formaction="{{ route('admin.users.update', $user) }}"
                                                name="_method" value="PUT"><i class="fa fa-ban"></i></button>
                                        @endif
                                        @can ('admin.users.update', $user)
                                            <a href="{{ route('admin.users.edit', $user) }}" title="@lang('btn.edit')" class="btn btn-link"><i class="fa fa-pencil"></i></a>
                                        @endcan
                                        @if ('owner' != $user->role and user()->can('admin.users.delete', $user))
                                            <button type="submit" title="@lang('btn.delete')" class="btn btn-link text-danger"
                                                onclick="return confirm('@lang('msg.sure')');"
                                                formaction="{{ route('admin.users.delete', $user) }}"
                                                name="_method" value="DELETE"
                                                ><i class="fa fa-trash-o"></i></button>
                                        @else
                                            <button type="button" title="@lang('btn.delete')" class="btn btn-link text-muted" disabled><i class="fa fa-trash-o"></i></button>
                                        @endif
                                    </div>
                                </td>
    						</tr>
						@endforeach
    					</tbody>
    				</table>
    			</div>

                @if ($users->hasPages())
        			<div class="card-footer">
        				<div class="row">
        					<div class="col col-md-4">
        						<!--div class="input-group {{ $errors->has('mass_action') ? ' has-error' : '' }}">
                                    <select name="mass_action" class="form-control">
        								<option value="" class="divider">-- @lang('action') --</option>
        								<option value="activate">@lang('activate_selected')</option>
        								<option value="lock">@lang('lock_selected')</option>
        								<option value="" class="divider" disabled="">===================</option>
        								<option value="delete">@lang('delete_selected')</option>
        								<option value="delete_inactive">@lang('delete_inactive')</option>
        							</select>
        							<div class="input-group-append">
        								<button type="submit" class="btn btn-outline-dark">@lang('btn.apply')</button>
        							</div>
        						</div-->
        					</div>
        					<div class="col col-md-8 text-right">
        						{{ $users->links('components.pagination') }}
        					</div>
        				</div>
        			</div>
                @endif
    		</form>
    	</div>
    	<!-- List of users: END -->
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
                'reqMethodName': 'core.users.search',
                'lId': 'suggestLoader',
                'hlr': 'true',
                'stCols': 2,
                'stColsClass': [ 'cleft', 'cright' ],
                'stColsHLR': [ true, false ],
            });
        });
    </script>
@endpush
