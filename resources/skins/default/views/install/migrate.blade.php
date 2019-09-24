@extends('install.layouts.app')

@section('action_title', __('header.menu.migrate'))

@section('card_body')
	<p>@lang('migrate.textblock')</p>
	<fieldset>
		<div id="migrate" class="alert alert-dark">
			{!! nl2br(session('flash_output_migrate')) !!}
		</div>
	</fieldset>
@endsection
