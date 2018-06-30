@extends('install.layouts.app')

@section('action_title', __('header.menu.finish'))

@section('card_body')
	<p>@lang('finish.textblock') <a href="#seed" data-toggle="collapse">@lang('btn.more')</a></p>
	<fieldset>
		<div id="seed" class="collapse alert alert-dark">
			{!! nl2br(session('flash_output_seed')) !!}
			{{-- {!! nl2br(session('flash_output_test_seed')) !!} --}}
		</div>
	</fieldset>
@endsection
