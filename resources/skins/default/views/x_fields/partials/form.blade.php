<div class="card card-default">
	<div class="card-header"><i class="fa fa-th-list"></i> @lang('legend.main')</div>
	<div class="card-body">

        <div class="form-group row {{ $errors->has('extensible') ? ' has-error' : '' }}">
			<label class="col-sm-7 control-label">@lang('extensible')</label>
			<div class="col-sm-5">
				<select name="extensible" class="form-control" required>
					@foreach($extensibles as $extensible)
					    <option value="{{ $extensible }}" {{ old('extensible', optional($x_field)->extensible) == $extensible ? 'selected' : '' }}>{{ $extensible }}</option>
						{{-- @lang($extensible) --}}
					@endforeach
				</select>
			</div>
		</div>

		<div class="form-group row {{ $errors->has('name') ? ' has-error' : '' }}">
			<label class="col-sm-7 control-label">@lang('name') <small class="form-text text-muted">@lang('name#descr')</small></label>
			<div class="col-sm-5">
				<input type="text" name="name" class="form-control" autocomplete="off" required {{ optional($x_field)->name ? 'readonly' : '' }}
                	value="{{ old('name', optional($x_field)->name) }}" />
			</div>
		</div>

		<div class="form-group row {{ $errors->has('type') ? ' has-error' : '' }}">
			<label class="col-sm-7 control-label">@lang('type') <small class="form-text text-muted">@lang('type#descr')</small></label>
			<div class="col-sm-5">
				<select name="type" class="form-control" required>
					@foreach($field_types as $k => $item)
					    <option value="{{ $item }}"
					        @if ($item == old('type', optional($x_field)->type))
								selected
							@endif
					    >@lang('type.'.$item)</option>
					@endforeach
				</select>
			</div>
		</div>

		<div class="form-group row {{ $errors->has('params') ? ' has-error' : '' }}">
			<label class="col-sm-7 control-label">@lang('params') <small class="form-text text-muted">@lang('params#descr')</small></label>
			<div class="col-sm-5">
				<!-- NO WRAP TEXTAREA CONTENT -->
				@if (optional($x_field)->params)
					<textarea name="params" rows="4" class="form-control">{{ old('params', optional($x_field)->getOriginal('params')) }}</textarea>
				@else
					<textarea name="params" rows="4" class="form-control">{{ old('params') }}</textarea>
				@endif
			</div>
		</div>
	</div>
</div>

<div class="card card-default">
	<div class="card-header"><i class="fa fa-th-list"></i> @lang('legend.display')</div>
	<div class="card-body">

		<div class="form-group row {{ $errors->has('title') ? ' has-error' : '' }}">
			<label class="col-sm-7 control-label">@lang('title')</label>
			<div class="col-sm-5">
				<input type="text" name="title" class="form-control" autocomplete="off" required
                	value="{{ old('title', optional($x_field)->title) }}" />
			</div>
		</div>

		<div class="form-group row {{ $errors->has('descr') ? ' has-error' : '' }}">
			<label class="col-sm-7 control-label">@lang('descr')</label>
			<div class="col-sm-5">
				<!-- NO WRAP TEXTAREA CONTENT -->
				<textarea name="descr" rows="4" onkeydown="return !(event.keyCode == 13)" class="form-control">{{ old('descr', optional($x_field)->descr) }}</textarea>
			</div>
		</div>

		<div class="form-group row {{ $errors->has('html_flags') ? ' has-error' : '' }}">
			<label class="col-sm-7 control-label">@lang('html_flags') <small class="form-text text-muted">@lang('html_flags#descr')</small></label>
			<div class="col-sm-5">
				<!-- NO WRAP TEXTAREA CONTENT -->
				<textarea name="html_flags" rows="4" onkeydown="return !(event.keyCode == 13)" class="form-control">{{ old('html_flags', optional($x_field)->html_flags) }}</textarea>
			</div>
		</div>
	</div>
</div>

<!-- SUBMIT Form -->
<div class="card">
	<div class="card-footer">
		<div class="row">
			<div class="col-sm-5 offset-sm-7">
				<button type="submit" title="Ctrl+S" class="btn btn-outline-success btn-bg-white">
					<span class="d-md-none"><i class="fa fa-floppy-o"></i></span>
					<span class="d-none d-md-inline">@lang(isset($x_field->id) ? 'btn.save' : 'btn.create')</span>
				</button>
				<a href="{{ (! empty($x_field->extensible) and \Route::has($route = 'admin.'.$x_field->extensible.'.index')) ? route($route) : route('admin.dashboard') }}" class="btn btn-outline-dark btn-bg-white">
                    <span class="d-lg-none"><i class="fa fa-ban"></i></span>
                    <span class="d-none d-lg-inline">@lang('btn.cancel')</span>
                </a>
			</div>
		</div>
	</div>
</div>
