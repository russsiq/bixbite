@extends('layouts.app')

@section('breadcrumb')
    @component('components.breadcrumbs')
        @slot('crumbs', [
            ['action' => 'x_fields.index', 'title' => 'x_fields'],
        ])
    @endcomponent
@endsection

@section('mainblock')
	<div class="card card-table">
		<div class="card-header d-flex d-print-none">
            <a href="{{ route('admin.x_fields.create') }}" class="btn btn-outline-dark"><i class="fa fa-plus"></i> @lang('btn.create')</a>
            <div class="btn-group ml-auto">
				<a href="{{ route('system_care.clearcache', 'x_fields') }}" title="@lang('clear')" class="btn btn-outline-dark"><i class="fa fa-recycle"></i></a>
			</div>
			<div class="btn-group ml-auto">
				<button type="button" title="@lang('btn.print')" class="btn btn-outline-dark" onclick="window.print();"><i class="fa fa-print"></i></button>
			</div>
		</div>

        <!-- Main content form -->
		<form action="" method="post" class="form-horizontal">
			@csrf

            <!-- List of x_fields: BEGIN -->
            <div class="card-body table-responsive">
				<table class="table table-sm">
					<thead>
						<tr>
							<th>#</th>
							<th>@lang('extensible')</th>
							<th>@lang('name')</th>
							<th>@lang('title')</th>
							<th>@lang('type')</th>
							<th>@lang('action')</th>
						</tr>
					</thead>
					<tbody>
					@foreach($x_fields as $k => $x_field)
						<tr>
							<td>{{ $x_field->id }}</td>
							<td>{{ $x_field->extensible }}</td>
							<td><a href="{{ route('admin.x_fields.edit', $x_field) }}" class="">{{ $x_field->name }}</a></td>
							<td>{{ $x_field->title }}</td>
							<td>{{ $x_field->type }}</td>
							<td class="text-right">
                                <div class="btn-group">
                                    <a href="{{ route('admin.x_fields.edit', $x_field) }}" title="@lang('btn.edit')" class="btn btn-link"><i class="fa fa-pencil"></i></a>
                                    <button type="submit" title="@lang('btn.delete')" class="btn btn-link text-danger"
                                        onclick="return confirm('@lang('msg.sure')');"
                                        formaction="{{ route('admin.x_fields.delete', $x_field) }}"
                                        name="_method" value="DELETE"
                                        ><i class="fa fa-trash-o"></i></button>
                                </div>
                            </td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
		</form>
	</div>
@endsection
