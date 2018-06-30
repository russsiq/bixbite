@extends('layouts.app')

@section('breadcrumb')
    @component('components.breadcrumbs')
        @slot('crumbs', [
            ['action' => 'settings.index', 'title' => 'settings'],
        ])
    @endcomponent
@endsection

@section('mainblock')
	<div class="card card-table">
		<div class="card-header d-flex d-print-none">
            <a href="{{ route('admin.settings.create') }}" class="btn btn-outline-dark"><i class="fa fa-plus"></i> @lang('btn.create')</a>
			<div class="btn-group ml-auto">
				{{-- <button type="button" title="@lang('btn.filter')" class="btn btn-outline-dark" data-toggle="collapse" data-target="#settings_filter"><i class="fa fa-filter"></i></button> --}}
				<button type="button" title="@lang('btn.print')" class="btn btn-outline-dark" onclick="window.print();"><i class="fa fa-print"></i></button>
			</div>
		</div>

        <!-- Main content form -->
		<form id="form_action" name="export" action="{{ route('admin.settings.export') }}" method="post" accept-charset="UTF-8" onsubmit="return confirm('@lang('msg.sure')');" class="form-horizontal">
			@csrf

            <!-- List of settings: BEGIN -->
            <div class="card-body table-responsive">
				<table class="table table-sm">
					<thead>
						<tr>
							{{-- <th><input type="checkbox" class="select-all" title="@lang('select_all')" /></th> --}}
							<th>#</th>
							<th>@lang('module')</th>
							<th>@lang('field_name')</th>
							<th>@lang('field_title')</th>
							<th>@lang('action')</th>
							<th class="text-left">@lang('field_section') -> @lang('fieldset')</th>
						</tr>
					</thead>
					<tbody>
					@foreach($settings as $k => $setting)
						<tr>
							{{-- <td class="{{ $errors->has('settings') ? ' has-error' : '' }}"><input name="settings[]" value="{{ $setting->id }}" type="checkbox" /></td> --}}
							<td>{{ $setting->id }}</td>
							<td><a href="{{ route('admin.settings.module', $setting->module->name) }}" class="">@lang($setting->module->name)</a></td>
							<td><a href="{{ route('admin.settings.edit', $setting) }}" class="">{{ $setting->name }}</a></td>
							<td>@lang($setting->name)</td>
							<td>{{ $setting->action }}</td>
							<td class="text-left">{{ $setting->action }} -> {{ $setting->section }} -> {{ $setting->fieldset }}</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
		</form>
	</div>
@endsection
