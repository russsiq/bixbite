@extends('assistant::_layouts.app')

@section('card_body')
	@lang('assistant::install.descriptions.common')

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
		<legend>@lang('assistant::install.forms.legends.enviroments')</legend>
		<div class="form-group row @error('APP_ENV') is-invalid @enderror">
			<label class="col-sm-3 col-form-label">APP_ENV</label>
			<div class="col-sm-9">
				<select class="form-control" name="APP_ENV">
					@foreach (['production', 'local', 'dev'] as $env)
						<option value="{{ $env }}">{{ $env }}</option>
					@endforeach
				</select>
				@error ('APP_ENV')
					<div class="invalid-feedback d-block">{{ $message }}</div>
				@enderror
			</div>
		</div>
	</fieldset>
@endsection
