@extends('assistant::_layouts.app')

@section('card_body')
	@if ('success' === session('status'))
		@lang('assistant::update.descriptions.complete')
		<div class="alert alert-info">
			<ul class="alert-list">
				<li>@lang('assistant::update.strings.currently_version', compact('currently_version'))</li>
			</ul>
		</div>
	@else
		<div class="alert alert-danger">@lang('assistant::assistant.messages.errors.denied_page')</div>
	@endif
@endsection
