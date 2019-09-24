@extends('install.layouts.app')

@section('action_title', __('header.menu.finish'))

@section('card_body')
	<p>@lang('finish.textblock')</p>
	<fieldset>
		<div id="seed" class="alert alert-dark">
			{!! nl2br(session('flash_output_seed')) !!}
			{{-- {!! nl2br(session('flash_output_test_seed')) !!} --}}
		</div>
	</fieldset>
@endsection
