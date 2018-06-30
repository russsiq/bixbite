@extends('install.layouts.app')

@section('action_title', __('header.menu.permission'))

@section('card_body')
	<p>@lang('perm.textblock')</p>
	<fieldset>
		<table class="table" style="vertical-align:top">
			<thead><tr><th>@lang('perm.minreq')</th><th></th><th>@lang('perm.globals')</th><th></th><th colspan="3">@lang('perm.files')</th></tr></thead>
			<tbody>
				<tr>
					<td class="p-0">
						<table class="table table-sm">
							<tbody>
								@foreach ($minreq as $key => $value)
									<tr>
										<td>@lang('perm.minreq.'.$key)</td>
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
										<td>@lang($key)</td>
										<td><b class="float-right text-{{ $flag ? 'danger' : 'success' }}">{{ $flag ? 'X' : '✓' }}</b></td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</td>

					<td></td>

					<td class="p-0">
						<table class="table table-sm">
							<tbody>
								@foreach ($chmod as $key => $flag)
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
			<tfoot>
				<tr>
					<td colspan="5" class="p-0">@lang('perm.textblock_2')</td>
				</tr>
			</tfoot>
		</table>
	</fieldset>
@endsection
