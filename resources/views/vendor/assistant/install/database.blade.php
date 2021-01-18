@extends('assistant::_layouts.app')

@section('card_body')
	@lang('assistant::install.descriptions.database')

	@if ($errors->has('database'))
		<div class="form-group">
			<div class="alert alert-danger">{{ $errors->first('database') }}</div>
		</div>
	@endif

	<fieldset>
		<div class="form-group row @error('DB_HOST') is-invalid @enderror">
            <label class="col-sm-6 control-label">@lang('assistant::install.forms.labels.DB_HOST')</label>
            <div class="col-sm-6">
                <input type="text" name="DB_HOST" value="{{ old('DB_HOST', '127.0.0.1') }}" class="form-control" />
				@error ('DB_HOST')
					<div class="invalid-feedback d-block">{{ $message }}</div>
				@enderror
            </div>
        </div>

        <div class="form-group row @error('DB_DATABASE') is-invalid @enderror">
            <label class="col-sm-6 col-form-label">@lang('assistant::install.forms.labels.DB_DATABASE')</label>
			<div class="col-sm-6">
                <input type="text" name="DB_DATABASE" value="{{ old('DB_DATABASE', 'bbcms') }}" class="form-control" />
				@error ('DB_DATABASE')
					<div class="invalid-feedback d-block">{{ $message }}</div>
				@enderror
            </div>
        </div>

        <div class="form-group row @error('DB_USERNAME') is-invalid @enderror">
            <label class="col-sm-6 control-label">@lang('assistant::install.forms.labels.DB_USERNAME')</label>
            <div class="col-sm-6">
                <input type="text" name="DB_USERNAME" value="{{ old('DB_USERNAME', 'root') }}" class="form-control" />
				@error ('DB_USERNAME')
					<div class="invalid-feedback d-block">{{ $message }}</div>
				@enderror
            </div>
        </div>

        <div class="form-group row @error('DB_PASSWORD') is-invalid @enderror">
            <label class="col-sm-6 col-form-label">@lang('assistant::install.forms.labels.DB_PASSWORD')</label>
			<div class="col-sm-6">
                <input type="text" name="DB_PASSWORD" value="" class="form-control" />
				@error ('DB_PASSWORD')
					<div class="invalid-feedback d-block">{{ $message }}</div>
				@enderror
            </div>
        </div>

        <div class="form-group row @error('DB_PREFIX') is-invalid @enderror">
            <label class="col-sm-6 control-label">@lang('assistant::install.forms.labels.DB_PREFIX')</label>
			<div class="col-sm-6">
                <input type="text" name="DB_PREFIX" value="{{ old('DB_PREFIX', 'bb_') }}" class="form-control" />
				@error ('DB_PREFIX')
					<div class="invalid-feedback d-block">{{ $message }}</div>
				@enderror
            </div>
        </div>

		<div class="form-group row @error('test_seed') is-invalid @enderror">
			<div class="col-sm-6"></div>
			<div class="col-sm-6">
				<label class="control-label">
					<input type="checkbox" name="test_seed" value="1" {{ old('test_seed') ? 'checked' : '' }} />
					@lang('assistant::install.forms.labels.test_seed')
				</label>
				@error ('test_seed')
					<div class="invalid-feedback d-block">{{ $message }}</div>
				@enderror
			</div>
		</div>
	</fieldset>
@endsection
