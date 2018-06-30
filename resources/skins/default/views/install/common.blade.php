@extends('install.layouts.app')

@section('action_title', __('header.menu.common'))

@section('card_body')
	@if ($errors->has('common'))
		<div class="form-group">
			<div class="alert alert-danger">{{ $errors->first('common') }}</div>
		</div>
	@endif
	<p>@lang('common.textblock')</p>
	<hr>
	<fieldset>
		<div class="form-group row{{ $errors->has('APP_NAME') ? ' has-error' : '' }}">
			<label class="col-sm-4 col-form-label">@lang('APP_NAME')</label>
			<div class="col-sm-8">
				<input type="text" name="APP_NAME" value="{{ old('APP_NAME', '') }}" placeholder="@lang('common.app_name.default')" class="form-control">
				@if ($errors->has('APP_NAME'))<div class="invalid-feedback d-block">{{ $errors->first('APP_NAME') }}</div>@endif
			</div>
		</div>
		<div class="form-group row{{ $errors->has('name') ? ' has-error' : '' }}">
			<label class="col-sm-4 col-form-label">@lang('common.name')</label>
			<div class="col-sm-8">
				<input type="text" name="name" value="{{ old('name', '') }}" placeholder="admin" class="form-control">
				@if ($errors->has('name'))<div class="invalid-feedback d-block">{{ $errors->first('name') }}</div>@endif
			</div>
		</div>
		<div class="form-group row{{ $errors->has('email') ? ' has-error' : '' }}">
			<label class="col-sm-4 col-form-label">@lang('common.email')</label>
			<div class="col-sm-8">
				<input type="email" name="email" value="{{ old('email', '') }}" placeholder="{{ $email }}" class="form-control">
				@if ($errors->has('email'))<div class="invalid-feedback d-block">{{ $errors->first('email') }}</div>@endif
			</div>
		</div>
		<div class="form-group row{{ $errors->has('password') ? ' has-error' : '' }}">
			<label class="col-sm-4 col-form-label">@lang('common.password')</label>
			<div class="col-sm-8">
				<input type="password" name="password" value="" placeholder="********" class="form-control">
				@if ($errors->has('password'))<div class="invalid-feedback d-block">{{ $errors->first('password') }}</div>@endif
			</div>
		</div>
	</fieldset>

	<hr>

	<fieldset>
		@if ($errors->has('APP_THEME'))
			<div class="form-group">
				<div class="alert alert-danger">{{ $errors->first('APP_THEME') }}</div>
			</div>
		@endif
		<div id="theme-card-list" class="row">
	    	@foreach ($themes as $key => $theme)
	    	<div class="col-12 col-lg-6 mb-4">
	            <div class="theme-card" style="background-image: url({{ $theme->screenshot ?? '//via.placeholder.com/350x250' }})">
	        		<div class="color-overlay clearfix">
	        			<div class="icon-block">
							<input type="radio" name="APP_THEME" value="{{ $theme->name }}" class="card-theme-radio" autocomplete="off" {{ $loop->first ? 'checked' : '' }}/>
	    				</div>
	        			<div class="theme-content">
	        				<div class="theme-header">
	        					<h3 class="theme-title">{{ $theme->id }}</h3>
	        					<h4 class="theme-info"><a href="{{ $theme->author_url }}" target="_blank">{{ $theme->author }}</a><br>v{{ $theme->version }} ({{ $theme->reldate }})</h4>
	        				</div>
	        				<p class="theme-desc">{{ $theme->title }}<br>{{ teaser($theme->description, 150) }}</p>
	        			</div>
	        		</div>
	    		</div>
	    	</div>
	        @endforeach
	    </div>
	</fieldset>

	<hr>

	<fieldset>
		<div class="form-group row{{ $errors->has('test_seed') ? ' has-error' : '' }}">
			<div class="col-sm-8 offset-sm-4">
				<label class="form-label">
					<input type="checkbox" name="test_seed" value="1" /> @lang('common.test_seed')
				</label>
				@if ($errors->has('test_seed'))<div class="invalid-feedback d-block">{{ $errors->first('test_seed') }}</div>@endif
			</div>
		</div>
			<div class="form-group row{{ $errors->has('original_theme') ? ' has-error' : '' }}">
				<div class="col-sm-8 offset-sm-4">
					<label class="form-label">
						<input type="checkbox" name="original_theme" value="1" /> @lang('common.original_theme')
					</label>
					@if ($errors->has('original_theme'))<div class="invalid-feedback d-block">{{ $errors->first('original_theme') }}</div>@endif
				</div>
			</div>
	</fieldset>
@endsection
