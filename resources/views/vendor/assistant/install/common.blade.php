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

    <input type="hidden" name="SANCTUM_STATEFUL_DOMAINS" value="{{ request()->getHttpHost() }}" />

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

	<fieldset>
		<legend>@lang('Register')</legend>
		<div class="mb-3 row @error('name') is-invalid @enderror">
			<label class="col-sm-3 col-form-label">@lang('Name')</label>
			<div class="col-sm-9">
				<input type="text" name="name" value="{{ old('name', '') }}" placeholder="admin" class="form-control" />
				@error ('name')
					<div class="invalid-feedback d-block">{{ $message }}</div>
				@enderror
			</div>
		</div>

		<div class="mb-3 row @error('email') is-invalid @enderror">
			<label class="col-sm-3 col-form-label">@lang('Email')</label>
			<div class="col-sm-9">
				<input type="email" name="email" value="{{ old('email', '') }}" placeholder="{{ $email }}" class="form-control" />
				@error ('email')
					<div class="invalid-feedback d-block">{{ $message }}</div>
				@enderror
			</div>
		</div>

		<div class="mb-3 row @error('password') is-invalid @enderror">
			<label class="col-sm-3 col-form-label">@lang('Password')</label>
			<div class="col-sm-9">
				<input type="password" name="password" value="" placeholder="********" class="form-control" autocomplete="new-password"  />
				@error ('password')
					<div class="invalid-feedback d-block">{{ $message }}</div>
				@enderror
			</div>
		</div>

		<div class="mb-3 row">
			<label class="col-sm-3 col-form-label">@lang('Confirm Password')</label>
			<div class="col-sm-9">
				<input type="password" name="password_confirmation" placeholder="********" class="form-control" autocomplete="new-password"  />
			</div>
		</div>
	</fieldset>
@endsection
