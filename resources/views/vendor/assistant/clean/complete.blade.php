@extends('assistant::_layouts.app')

@section('card_body')
	{{--
		Обратите внимание, что после кэширования маршрутов или кэширования конфигураций
		становится невозможным передача/получение сообщений через сессии.
	--}}
	@if ($messages = cache()->pull($messages_cache_key))
		@lang('assistant::clean.descriptions.complete')
		<div class="alert alert-info">
			<ul class="alert-list">
				@foreach ($messages as $message)
					<li>@lang("assistant::clean.messages.{$message}")</li>
				@endforeach
			</ul>
		</div>
	@else
		<div class="alert alert-danger">@lang('assistant::assistant.messages.errors.denied_page')</div>
	@endif
@endsection
