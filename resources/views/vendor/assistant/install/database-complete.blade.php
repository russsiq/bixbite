@extends('assistant::_layouts.app')

@section('card_body')
	@if ($messages = session('messages'))
		@lang('assistant::install.descriptions.database-complete')
		<fieldset>
			<pre class="alert alert-dark">{{ $messages['migrate'] }}</pre>
			@foreach ($messages['seeds'] as $message)
				<pre class="alert alert-dark">{{ $message }}</pre>
			@endforeach
		</fieldset>
	@else
		<div class="alert alert-danger">@lang('assistant::assistant.messages.errors.denied_page')</div>
	@endif
@endsection
