@extends('install.layouts.app')

@section('action_title', __('header.menu.welcome'))

@section('card_body')
	<p>@lang('welcome.textblock')</p>
	<fieldset>
		<div class="form-group{{ $errors->has('agree') ? ' has-error' : '' }}">
			<label><input type="checkbox" name="agree" value="1" /> @lang('welcome.licence.accept')</label>
            @if ($errors->has('agree'))<div class="invalid-feedback d-block">{{ $errors->first('agree') }}</div>@endif
		</div>
	</fieldset>
@endsection
