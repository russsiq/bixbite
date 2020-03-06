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
		<legend>@lang('auth.register')</legend>
		<div class="form-group row @error('name') is-invalid @enderror">
			<label class="col-sm-3 col-form-label">@lang('auth.name')</label>
			<div class="col-sm-9">
				<input type="text" name="name" value="{{ old('name', '') }}" placeholder="admin" class="form-control" />
				@error ('name')
					<div class="invalid-feedback d-block">{{ $message }}</div>
				@enderror
			</div>
		</div>

		<div class="form-group row @error('email') is-invalid @enderror">
			<label class="col-sm-3 col-form-label">@lang('auth.email')</label>
			<div class="col-sm-9">
				<input type="email" name="email" value="{{ old('email', '') }}" placeholder="{{ $email }}" class="form-control" />
				@error ('email')
					<div class="invalid-feedback d-block">{{ $message }}</div>
				@enderror
			</div>
		</div>

		<div class="form-group row @error('password') is-invalid @enderror">
			<label class="col-sm-3 col-form-label">@lang('auth.password')</label>
			<div class="col-sm-9">
				<input type="password" name="password" value="" placeholder="********" class="form-control" autocomplete="new-password"  />
				@error ('password')
					<div class="invalid-feedback d-block">{{ $message }}</div>
				@enderror
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-3 col-form-label">@lang('auth.password_confirmation')</label>
			<div class="col-sm-9">
				<input type="password" name="password_confirmation" placeholder="********" class="form-control" autocomplete="new-password"  />
			</div>
		</div>
	</fieldset>

	<fieldset>
		<legend>@lang('assistant::install.forms.legends.theme')</legend>

		@error ('APP_THEME')
			<div class="form-group">
				<div class="alert alert-danger">{{ $message }}</div>
			</div>
		@enderror

		<div id="theme-card-list" class="row">
	    	@foreach (collect(select_dir(resource_path('themes')))->map('theme_version')->filter() as $key => $theme)
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

		<hr>

		<div class="form-group row @error('original_theme') is-invalid @enderror">
			<div class="col-sm-9 offset-sm-3">
				<label class="col-form-label">
					<input type="checkbox" name="original_theme" value="1" {{ old('original_theme') ? 'checked' : '' }} /> @lang('assistant::install.forms.labels.original_theme')
				</label>
				@error ('original_theme')
					<div class="invalid-feedback d-block">{{ $message }}</div>
				@enderror
			</div>
		</div>
	</fieldset>
@endsection
