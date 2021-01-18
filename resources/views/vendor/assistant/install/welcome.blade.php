@extends('assistant::_layouts.app')

@section('card_body')
	@lang('assistant::install.descriptions.welcome')

	<fieldset>
		<legend>@lang('assistant::install.forms.legends.welcome')</legend>
		<div class="form-group row @error('APP_NAME') is-invalid @enderror">
			<label class="col-sm-3 col-form-label">@lang('assistant::install.forms.labels.APP_NAME')</label>
			<div class="col-sm-9">
				<input type="text" name="APP_NAME" value="{{ old('APP_NAME', '') }}" placeholder="@lang('assistant::install.forms.placeholders.APP_NAME')" class="form-control"  />
				@error ('APP_NAME')
					<div class="invalid-feedback d-block">{{ $message }}</div>
				@enderror
			</div>
		</div>

		<div class="form-group row @error('licence') is-invalid @enderror">
			<div class="col-sm-9 offset-sm-3">
				<label><input type="checkbox" name="licence" value="1" /> @lang('assistant::install.forms.labels.licence')</label>
				@error ('licence')
					<div class="invalid-feedback d-block">{{ $message }}</div>
				@enderror
			</div>
		</div>
	</fieldset>
@endsection
