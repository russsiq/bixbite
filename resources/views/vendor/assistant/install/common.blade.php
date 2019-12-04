@extends('assistant::_layouts.app')

@section('action_title', __('header.menu.common'))

@section('card_body')
	@if ($errors->any())
	    <div class="alert alert-danger">
	        <ul>
	            @foreach ($errors->all() as $error)
	                <li>{{ $error }}</li>
	            @endforeach
	        </ul>
	    </div>
	@endif

	<p>@lang('common.textblock')</p>

	<fieldset>
		<legend>@lang('legend.organization')</legend>
		<div class="form-group row{{ $errors->has('APP_NAME') ? ' has-error' : '' }}">
			<label class="col-sm-3 col-form-label">@lang('APP_NAME')</label>
			<div class="col-sm-9">
				<input type="text" name="APP_NAME" value="{{ old('APP_NAME', '') }}" placeholder="@lang('common.app_name.default')" class="form-control"  />
				@if ($errors->has('APP_NAME'))<div class="invalid-feedback d-block">{{ $errors->first('APP_NAME') }}</div>@endif
			</div>
		</div>
	</fieldset>
@endsection
