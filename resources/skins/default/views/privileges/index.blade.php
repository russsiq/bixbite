@extends('layouts.app')

@section('breadcrumb')
    @component('components.breadcrumbs')
        @slot('crumbs', [
            ['action' => 'users.index', 'title' => 'users'], ['action' => 'privileges.index', 'title' => 'privileges'],
        ])
    @endcomponent
@endsection

@section('mainblock')
    <div class="card card-table">
        <div class="card-header d-flex d-print-none">
            @can ('admin.privileges.create')
                <a href="{{ route('admin.privileges.create') }}" class="btn btn-outline-dark"><i class="fa fa-plus"></i> @lang('btn.create')</a>
            @endcan
            <div class="btn-group d-flex ml-auto">
                @can ('admin.xfields.modify')
                    <a href="{{-- route('admin.xfields.module', 'categories') --}}" title="@lang('btn.add_xfield')" class="btn btn-outline-dark"><span class="as-icon">χφ</span></a>
                @endcan
                @can ('admin.settings.details')
                    <a href="{{ route('admin.settings.module', 'categories') }}" title="@lang('settings')" class="btn btn-outline-dark"><i class="fa fa-cogs"></i></a>
                @endcan
            </div>
            <div class="btn-group d-flex ml-auto">{{--  --}}</div>
        </div>

        <!-- Main content form -->
        <form id="form_action" name="mass_update" action="{{ route('admin.privileges.mass_update') }}" method="post" accept-charset="UTF-8" onsubmit="return confirm('@lang('msg.sure')');" class="not_form-horizontal">
            @csrf

            <!-- List of privileges: BEGIN -->
            <div class="card-body table-responsive">
                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th>@lang('privilege')</th>
                            <th>@lang('description')</th>
                            @foreach($roles as $role)
                            <th>
                                <label class="control-label" style="cursor:pointer;margin-bottom:0">
                                    <input type="checkbox" class="select-all" />&nbsp; {{ $role }}
                                </label>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($privileges as $privilege)
                        <tr>
                            <td>
                                <label class="control-label mb-0" style="cursor:pointer">
                                    <input type="checkbox" class="select-all" />&nbsp; {{ $privilege->privilege }}
                                </label>
                            </td>
                            <td>{{ $privilege->description ?: __('privileges.' . $privilege->privilege) }}</td>
                            @foreach($roles as $role)
                            <td>
                                <label class="control-label" style="cursor:pointer">
                                    <input type="checkbox" name="{{ $role }}[]" value="{{ $privilege->id }}" @if($privilege->$role) checked @endif />
                                </label>
                            </td>
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <button type="submit" title="Ctrl+S" class="btn btn-outline-success btn-bg-white">
					<span class="d-md-none"><i class="fa fa-floppy-o"></i></span>
					<span class="d-none d-md-inline">@lang('btn.save')</span>
				</button>
            </div>
        </form>
    </div>
@endsection
