@extends('assistant::_layouts.app')

@section('card_body')
	@lang('assistant::install.descriptions.permission')

	<fieldset>
		<table class="table">
			<thead>
				<tr>
					<th>@lang('assistant::install.strings.requirements')</th>
					<th></th>
					<th>@lang('assistant::install.strings.globals')</th>
					<th></th>
					<th colspan="3">@lang('assistant::install.strings.files')</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="p-0">
						<table class="table table-sm">
							<tbody>
								@foreach ($requirements as $key => $value)
									<tr>
										<td>@lang('assistant::install.strings.'.$key)</td>
										<td><b class="text-{{ $value ? 'success' : 'danger' }}">{{ $value ? '✓' : 'X' }}</b></td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</td>

					<td></td>

					<td class="p-0">
						<table class="table table-sm">
							<tbody>
								@foreach ($globals as $key => $flag)
									<tr>
										<td>{{ $key }}</td>
										<td><b class="text-{{ $flag ? 'success' : 'danger' }}">{{ $flag ? '✓' : 'X' }}</b></td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</td>

					<td></td>

					<td class="p-0">
						<table class="table table-sm">
							<tbody>
								@foreach ($permissions as $key => $flag)
									<tr>
										<td>{{ $key }}</td>
										<td>{{ $flag->perm }}</td>
										<td><b class="text-{{ $flag->status ? 'success' : 'danger' }}">{{ $flag->status ? '✓' : 'X' }}</b></td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
	</fieldset>
@endsection
