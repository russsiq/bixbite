@extends('assistant::_layouts.app')

@section('card_body')
	@lang('assistant::clean.descriptions.welcome')

	@if ($errors->any())
	    <div class="alert alert-danger">
	        <ul class="alert-list">
	            @foreach ($errors->all() as $error)
	                <li>{{ $error }}</li>
	            @endforeach
	        </ul>
	    </div>
	@endif

	<fieldset>
		<legend>@lang('assistant::clean.forms.legends.clear')</legend>
		<div class="form-group @error('clear_cache') is-invalid @enderror">
			<label><input type="checkbox" name="clear_cache" value="1" /> @lang('assistant::clean.forms.attributes.clear_cache')</label>
			@error ('clear_cache')
				<div class="invalid-feedback d-block">{{ $message }}</div>
			@enderror
		</div>

		<div class="form-group @error('clear_config') is-invalid @enderror">
			<label><input type="checkbox" name="clear_config" value="1" /> @lang('assistant::clean.forms.attributes.clear_config')</label>
			@error ('clear_config')
				<div class="invalid-feedback d-block">{{ $message }}</div>
			@enderror
		</div>

		<div class="form-group @error('clear_route') is-invalid @enderror">
			<label><input type="checkbox" name="clear_route" value="1" /> @lang('assistant::clean.forms.attributes.clear_route')</label>
			@error ('clear_route')
				<div class="invalid-feedback d-block">{{ $message }}</div>
			@enderror
		</div>

		<div class="form-group @error('clear_view') is-invalid @enderror">
			<label><input type="checkbox" name="clear_view" value="1" /> @lang('assistant::clean.forms.attributes.clear_view')</label>
			@error ('clear_view')
				<div class="invalid-feedback d-block">{{ $message }}</div>
			@enderror
		</div>

		<div class="form-group @error('clear_compiled') is-invalid @enderror">
			<label><input type="checkbox" name="clear_compiled" value="1" /> @lang('assistant::clean.forms.attributes.clear_compiled')</label>
			@error ('clear_compiled')
				<div class="invalid-feedback d-block">{{ $message }}</div>
			@enderror
		</div>
	</fieldset>

	<fieldset>
		<legend>@lang('assistant::clean.forms.legends.cache')</legend>
		<div class="form-group @error('cache_config') is-invalid @enderror">
			<label><input type="checkbox" name="cache_config" value="1" /> @lang('assistant::clean.forms.attributes.cache_config')</label>
			@error ('cache_config')
				<div class="invalid-feedback d-block">{{ $message }}</div>
			@enderror
		</div>

		<div class="form-group @error('cache_route') is-invalid @enderror">
			<label><input type="checkbox" name="cache_route" value="1" /> @lang('assistant::clean.forms.attributes.cache_route')</label>
			@error ('cache_route')
				<div class="invalid-feedback d-block">{{ $message }}</div>
			@enderror
		</div>

		<div class="form-group @error('cache_view') is-invalid @enderror">
			<label><input type="checkbox" name="cache_view" value="1" /> @lang('assistant::clean.forms.attributes.cache_view')</label>
			@error ('cache_view')
				<div class="invalid-feedback d-block">{{ $message }}</div>
			@enderror
		</div>
	</fieldset>

	<fieldset>
		<legend>@lang('assistant::clean.forms.legends.optimize')</legend>
		<div class="form-group @error('complex_optimize') is-invalid @enderror">
			<label><input type="checkbox" name="complex_optimize" value="1" /> @lang('assistant::clean.forms.labels.complex_optimize')</label>
			@error ('complex_optimize')
				<div class="invalid-feedback d-block">{{ $message }}</div>
			@enderror
		</div>
	</fieldset>
@endsection
