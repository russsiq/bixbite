@extends('assistant::_layouts.app')

@section('card_body')
	@lang('assistant::update.descriptions.welcome')

	@if ($errors->any())
	    <div class="alert alert-danger">
	        <ul class="alert-list">
	            @foreach ($errors->all() as $error)
	                <li>{{ $error }}</li>
	            @endforeach
	        </ul>
	    </div>
	@endif

	<div class="alert alert-info">
		<ul class="alert-list">
			<li>@lang('assistant::update.strings.installed_date_time', compact('installed_at'))</li>
			<li>@lang('assistant::update.strings.currently_version', compact('currently_version'))</li>
			@if ($is_new_version_available)
				<li>@lang('assistant::update.strings.available_version', compact('available_version'))</li>
			@else
				<li>@lang('assistant::update.strings.is_actual_version')</li>
			@endif
		</ul>
	</div>
@endsection
