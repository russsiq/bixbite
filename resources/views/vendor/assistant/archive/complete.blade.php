@extends('assistant::_layouts.app')

@section('card_body')
	@if ('success' === session('status'))
		@lang('assistant::archive.descriptions.complete')
		<div class="alert alert-info">
			<ul class="alert-list">
				@foreach (session('messages') as $message)
					<li>{{ $message }}</li>
				@endforeach
			</ul>
		</div>
	@else
		<div class="alert alert-danger">@lang('assistant::assistant.messages.errors.denied_page')</div>
	@endif
@endsection
