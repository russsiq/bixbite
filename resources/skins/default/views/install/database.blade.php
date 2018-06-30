@extends('install.layouts.app')

@section('action_title', __('header.menu.database'))

@section('card_body')
	@if ($errors->has('database'))
		<div class="form-group">
			<div class="alert alert-danger">{{ $errors->first('database') }}</div>
		</div>
	@endif
	<p>@lang('database.textblock')</p>
	<fieldset>
		<div class="form-group row{{ $errors->has('DB_HOST') ? ' has-error' : '' }}">
            <label class="col-sm-6 control-label">@lang('DB_HOST')<small class="form-text text-muted">@lang('DB_HOST#desc')</small></label>
            <div class="col-sm-6">
                <input type="text" name="DB_HOST" value="{{ old('DB_HOST', '127.0.0.1') }}" class="form-control" />
                @if ($errors->has('DB_HOST'))<div class="invalid-feedback d-block">{{ $errors->first('DB_HOST') }}</div>@endif
            </div>
        </div>

        <div class="form-group row{{ $errors->has('DB_DATABASE') ? ' has-error' : '' }}">
            <label class="col-sm-6 col-form-label">@lang('DB_DATABASE')</label>
			<div class="col-sm-6">
                <input type="text" name="DB_DATABASE" value="{{ old('DB_DATABASE', 'bbcms') }}" class="form-control" />
                @if ($errors->has('DB_DATABASE'))<div class="invalid-feedback d-block">{{ $errors->first('DB_DATABASE') }}</div>@endif
            </div>
        </div>

        <div class="form-group row{{ $errors->has('DB_USERNAME') ? ' has-error' : '' }}">
            <label class="col-sm-6 control-label">@lang('DB_USERNAME')<small class="form-text text-muted">@lang('DB_USERNAME#desc')</small></label>
            <div class="col-sm-6">
                <input type="text" name="DB_USERNAME" value="{{ old('DB_USERNAME', 'root') }}" class="form-control" />
                @if ($errors->has('DB_USERNAME'))<div class="invalid-feedback d-block">{{ $errors->first('DB_USERNAME') }}</div>@endif
            </div>
        </div>

        <div class="form-group row{{ $errors->has('DB_PASSWORD') ? ' has-error' : '' }}">
            <label class="col-sm-6 col-form-label">@lang('DB_PASSWORD')</label>
			<div class="col-sm-6">
                <input type="text" name="DB_PASSWORD" value="" class="form-control" />
                @if ($errors->has('DB_PASSWORD'))<div class="invalid-feedback d-block">{{ $errors->first('DB_PASSWORD') }}</div>@endif
            </div>
        </div>

        <div class="form-group row{{ $errors->has('DB_PREFIX') ? ' has-error' : '' }}">
            <label class="col-sm-6 control-label">@lang('DB_PREFIX')<small class="form-text text-muted">@lang('DB_PREFIX#desc')</small></label>
			<div class="col-sm-6">
                <input type="text" name="DB_PREFIX" value="{{ old('DB_PREFIX', 'bb_') }}" class="form-control" />
                @if ($errors->has('DB_PREFIX'))<div class="invalid-feedback d-block">{{ $errors->first('DB_PREFIX') }}</div>@endif
            </div>
        </div>
	</fieldset>
@endsection
