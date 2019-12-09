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
		<legend>@lang('assistant::install.forms.legends.organization')</legend>
		<div class="form-group row{{ $errors->has('APP_NAME') ? ' has-error' : '' }}">
			<label class="col-sm-3 col-form-label">@lang('assistant::install.forms.labels.APP_NAME')</label>
			<div class="col-sm-9">
				<input type="text" name="APP_NAME" value="{{ old('APP_NAME', '') }}" placeholder="@lang('assistant::install.forms.placeholders.APP_NAME')" class="form-control"  />
				@if ($errors->has('APP_NAME'))
					<div class="invalid-feedback d-block">{{ $errors->first('APP_NAME') }}</div>
				@endif
			</div>
		</div>

		<div class="form-group row{{ $errors->has('ORG_NAME') ? ' has-error' : '' }}">
			<label class="col-sm-3 col-form-label">@lang('assistant::install.forms.labels.ORG_NAME')</label>
			<div class="col-sm-9">
				<input type="text" name="ORG_NAME" value="{{ old('ORG_NAME', '') }}" class="form-control" />
				@if ($errors->has('ORG_NAME'))
					<div class="invalid-feedback d-block">{{ $errors->first('ORG_NAME') }}</div>
				@endif
			</div>
		</div>

		<div class="form-group row{{ $errors->has('ORG_ADDRESS_LOCALITY') ? ' has-error' : '' }}">
			<label class="col-sm-3 control-label">@lang('assistant::install.forms.labels.ORG_ADDRESS_LOCALITY')</label>
			<div class="col-sm-9">
				<input type="text" name="ORG_ADDRESS_LOCALITY" value="{{ old('ORG_ADDRESS_LOCALITY', '') }}" class="form-control" />
				@if ($errors->has('ORG_ADDRESS_LOCALITY'))
					<div class="invalid-feedback d-block">{{ $errors->first('ORG_ADDRESS_LOCALITY') }}</div>
				@endif
			</div>
		</div>

		<div class="form-group row{{ $errors->has('ORG_ADDRESS_STREET') ? ' has-error' : '' }}">
			<label class="col-sm-3 control-label">@lang('assistant::install.forms.labels.ORG_ADDRESS_STREET')</label>
			<div class="col-sm-9">
				<input type="text" name="ORG_ADDRESS_STREET" value="{{ old('ORG_ADDRESS_STREET', '') }}" class="form-control" />
				@if ($errors->has('ORG_ADDRESS_STREET'))
					<div class="invalid-feedback d-block">{{ $errors->first('ORG_ADDRESS_STREET') }}</div>
				@endif
			</div>
		</div>

		<div class="form-group row{{ $errors->has('ORG_CONTACT_TELEPHONE') ? ' has-error' : '' }}">
			<label class="col-sm-3 col-form-label">@lang('assistant::install.forms.labels.ORG_CONTACT_TELEPHONE')</label>
			<div class="col-sm-9">
				<input type="text" name="ORG_CONTACT_TELEPHONE" value="{{ old('ORG_CONTACT_TELEPHONE', '') }}" class="form-control" />
				@if ($errors->has('ORG_CONTACT_TELEPHONE'))
					<div class="invalid-feedback d-block">{{ $errors->first('ORG_CONTACT_TELEPHONE') }}</div>
				@endif
			</div>
		</div>

		<div class="form-group row{{ $errors->has('ORG_CONTACT_EMAIL') ? ' has-error' : '' }}">
			<label class="col-sm-3 col-form-label">@lang('assistant::install.forms.labels.ORG_CONTACT_EMAIL')</label>
			<div class="col-sm-9">
				<input type="email" name="ORG_CONTACT_EMAIL" value="{{ old('ORG_CONTACT_EMAIL', '') }}" class="form-control"  />
				@if ($errors->has('ORG_CONTACT_EMAIL'))
					<div class="invalid-feedback d-block">{{ $errors->first('ORG_CONTACT_EMAIL') }}</div>
				@endif
			</div>
		</div>
	</fieldset>

	<fieldset>
		<legend>@lang('auth.register')</legend>
		<div class="form-group row{{ $errors->has('name') ? ' has-error' : '' }}">
			<label class="col-sm-3 col-form-label">@lang('auth.name')</label>
			<div class="col-sm-9">
				<input type="text" name="name" value="{{ old('name', '') }}" placeholder="admin" class="form-control" />
				@if ($errors->has('name'))
					<div class="invalid-feedback d-block">{{ $errors->first('name') }}</div>
				@endif
			</div>
		</div>

		<div class="form-group row{{ $errors->has('email') ? ' has-error' : '' }}">
			<label class="col-sm-3 col-form-label">@lang('auth.email')</label>
			<div class="col-sm-9">
				<input type="email" name="email" value="{{ old('email', '') }}" placeholder="{{ $email }}" class="form-control" />
				@if ($errors->has('email'))
					<div class="invalid-feedback d-block">{{ $errors->first('email') }}</div>
				@endif
			</div>
		</div>

		<div class="form-group row{{ $errors->has('password') ? ' has-error' : '' }}">
			<label class="col-sm-3 col-form-label">@lang('auth.password')</label>
			<div class="col-sm-9">
				<input type="password" name="password" value="" placeholder="********" class="form-control" autocomplete="new-password"  />
				@if ($errors->has('password'))
					<div class="invalid-feedback d-block">{{ $errors->first('password') }}</div>
				@endif
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-3 col-form-label">@lang('auth.password_confirmation')</label>
			<div class="col-sm-9">
				<input type="password" name="password_confirmation" placeholder="********" class="form-control" autocomplete="new-password"  />
			</div>
		</div>
	</fieldset>
@endsection
