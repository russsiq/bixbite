@extends('assistant::_layouts.app')

@section('card_body')
@lang('assistant::archive.descriptions.welcome')

@if ($errors->any())
<div class="alert alert-danger">
	<ul class="alert-list">
		@foreach ($errors->all() as $error)
		<li>{{ $error }}</li>
		@endforeach
	</ul>
</div>
@endif

<div class="tabs">
	<input id="first_tab" type="radio" name="{{ $operator }}" value="backup" checked />
	<label for="first_tab">
		@lang('assistant::archive.forms.legends.backup')</label>

	<input id="second_tab" type="radio" name="{{ $operator }}" value="restore" />
	<label for="second_tab">
		@lang('assistant::archive.forms.legends.restore')</label>

	<fieldset id="first_tab_content">
		<div class="form-group @error('backup_database') is-invalid @enderror">
			<label><input type="checkbox" name="backup[]" value="database" />
				@lang('assistant::archive.forms.labels.backup_database')</label>
			@error ('backup_database')
			<div class="invalid-feedback d-block">{{ $message }}</div>
			@enderror
		</div>

		<div class="form-group @error('backup_system') is-invalid @enderror">
			<label><input type="checkbox" name="backup[]" value="system" />
				@lang('assistant::archive.forms.labels.backup_system')</label>
			@error ('backup_system')
			<div class="invalid-feedback d-block">{{ $message }}</div>
			@enderror
		</div>

		<div class="form-group @error('backup_theme') is-invalid @enderror">
			<label><input type="checkbox" name="backup[]" value="theme" />
				@lang('assistant::archive.forms.labels.backup_theme')</label>
			@error ('backup_theme')
			<div class="invalid-feedback d-block">{{ $message }}</div>
			@enderror
		</div>

		<div class="form-group @error('backup_uploads') is-invalid @enderror">
			<label><input type="checkbox" name="backup[]" value="uploads" />
				@lang('assistant::archive.forms.labels.backup_uploads')</label>
			@error ('backup_uploads')
			<div class="invalid-feedback d-block">{{ $message }}</div>
			@enderror
		</div>
	</fieldset>

	<fieldset id="second_tab_content">
		<div class="form-group @error('restore_database') is-invalid @enderror">
			<label><input type="checkbox" name="restore[]" value="database" />
				@lang('assistant::archive.forms.labels.restore_database')</label>
			@error ('restore_database')
			<div class="invalid-feedback d-block">{{ $message }}</div>
			@enderror
		</div>

		{{--
		<div class="form-group @error('restore_system') is-invalid @enderror">
			<label><input type="checkbox" name="restore[]" value="system" />
				@lang('assistant::archive.forms.labels.restore_system')</label>
			@error ('restore_system')
			<div class="invalid-feedback d-block">{{ $message }}</div>
			@enderror
		</div>
		--}}

		<div class="form-group @error('restore_theme') is-invalid @enderror">
			<label><input type="checkbox" name="restore[]" value="theme" />
				@lang('assistant::archive.forms.labels.restore_theme')</label>
			@error ('restore_theme')
			<div class="invalid-feedback d-block">{{ $message }}</div>
			@enderror
		</div>

		<div class="form-group @error('restore_uploads') is-invalid @enderror">
			<label><input type="checkbox" name="restore[]" value="uploads" />
				@lang('assistant::archive.forms.labels.restore_uploads')</label>
			@error ('restore_uploads')
			<div class="invalid-feedback d-block">{{ $message }}</div>
			@enderror
		</div>

		<div class="form-group row @error('filename') is-invalid @enderror">
			<label class="col-sm-12 col-form-label">
				@lang('assistant::archive.forms.labels.filename')</label>
			<div class="col-sm-12">
				<select name="filename" class="form-control">
					@foreach ($backups as $file)
					<option value="{{ $file->basename }}">{{ $file->basename }} ({{ $file->size }})</option>
					@endforeach
				</select>
				@error ('filename')
				<div class="invalid-feedback d-block">{{ $message }}</div>
				@enderror
			</div>
		</div>
	</fieldset>
</div>

<style media="screen">
	.tabs {

	}

	.tabs>fieldset {
		display: none;
		margin-top: 0;
	}

	.tabs>input {
		display: none;
		position: absolute;
	}

	.tabs>label {
		border: 1px solid var(--primary);
	    transition: all .3s ease 0s;
	    padding: .5rem .75rem;
	    line-height: 1.6;
	    margin-bottom: -1px;
	}

	.tabs>label:hover {
		cursor: pointer;
		opacity: 0.6;
	}

	.tabs>label:hover,
	.tabs>input:checked+label {
	    color: var(--white);
	    border-bottom: 1px solid var(--primary);
	    background: var(--primary);
	}

	#first_tab:checked~#first_tab_content,
	#second_tab:checked~#second_tab_content {
		display: block;
	}
</style>
@endsection
