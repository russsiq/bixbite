<div class="card card-default">
	<div class="card-header"><i class="fa fa-th-list"></i> @lang('legend.location')</div>
	<div class="card-body">
        <div class="form-group row {{ $errors->has('module_name') ? ' has-error' : '' }}">
			<label class="col-sm-7 control-label">@lang('module')</label>
			<div class="col-sm-5">
				<select name="module_name" class="form-control">
					@foreach($modules as $module)
					    <option value="{{ $module->name }}" {{ old('module_name', optional($setting)->module_name) == $module->name ? 'selected' : '' }}>@lang($module->name)</option>
					@endforeach
				</select>
			</div>
		</div>

		{{-- <div class="form-group row {{ $errors->has('action') ? ' has-error' : '' }}">
			<label class="col-sm-7 control-label">@lang('action') <sup class="text-danger">*</sup></label>
			<div class="col-sm-5">
				<input type="text" name="action" class="form-control" required
                value="{{ old('action', optional($setting)->action) }}" />
			</div>
		</div> --}}

		<div class="form-group row {{ $errors->has('section') ? ' has-error' : '' }}">
			<label class="col-sm-7 control-label">@lang('field_section_with_trans') <small class="form-text text-muted">@lang('field_section#descr')</small></label>
			<div class="col-sm-5">
				<input list="sections" type="text" name="section" class="form-control" placeholder="main ..." autocomplete="off"
                	value="{{ old('section', optional($setting)->section) }}" />
				<datalist id="sections">
					@foreach($datalist->sections as $item)
						<option value="{{ $item }}">
					@endforeach
				</datalist>
			</div>
		</div>

		<div class="form-group row {{ $errors->has('section_lang') ? ' has-error' : '' }}">
			<label class="col-sm-7 control-label"></label>
			<div class="col-sm-5">
				<input list="sections_lang" type="text" name="section_lang" class="form-control" placeholder="@lang('section.main') ..." autocomplete="off"
                	value="@lang(old('section_lang', optional($setting)->section ? 'section.' . ($setting->section ?? 'main') : ''))" />
				<datalist id="sections_lang">
					@foreach($datalist->sections as $item)
						<option value="@lang('section.' . $item)">
					@endforeach
				</datalist>
			</div>
		</div>

		<div class="form-group row {{ $errors->has('fieldset') ? ' has-error' : '' }}">
			<label class="col-sm-7 control-label">@lang('fieldset_with_trans') <small class="form-text text-muted">@lang('fieldset#descr')</small></label>
			<div class="col-sm-5">
				<input list="fieldsets" type="text" name="fieldset" class="form-control" placeholder="general ..." autocomplete="off"
                	value="{{ old('fieldset', optional($setting)->fieldset) }}" />
				<datalist id="fieldsets">
					@foreach($datalist->fieldsets as $item)
						<option value="{{ $item }}">
					@endforeach
				</datalist>
			</div>
		</div>

		<div class="form-group row {{ $errors->has('legend_lang') ? ' has-error' : '' }}">
			<label class="col-sm-7 control-label"></label>
			<div class="col-sm-5">
				<input list="legends_lang" name="legend_lang" class="form-control" placeholder="@lang('legend.general') ..."
                	value="@lang(old('legend_lang', optional($setting)->fieldset ? 'legend.' . ($setting->fieldset ?? 'general') : ''))" />
				<datalist id="legends_lang">
					@foreach($datalist->fieldsets as $item)
						<option value="@lang('legend.' . $item)">
					@endforeach
				</datalist>
			</div>
		</div>
	</div>
</div>

<div class="card card-default">
	<div class="card-header"><i class="fa fa-th-list"></i> @lang('legend.required')</div>
	<div class="card-body">

		<div class="form-group row {{ $errors->has('name') ? ' has-error' : '' }}">
			<label class="col-sm-7 control-label">@lang('field_name') <small class="form-text text-muted">@lang('field_name#descr')</small></label>
			<div class="col-sm-5">
				<input type="text" name="name" class="form-control" autocomplete="off"
                	value="{{ old('name', optional($setting)->name) }}" />
			</div>
		</div>

		<div class="form-group row {{ $errors->has('type') ? ' has-error' : '' }}">
			<label class="col-sm-7 control-label">@lang('field_type') <small class="form-text text-muted">@lang('field_type#descr')</small></label>
			<div class="col-sm-5">
				<select name="type" class="form-control">
					@foreach($field_types as $k => $item)
					    <option value="{{ $item }}"
							@if ($item == old('type', optional($setting)->type))
								selected
							@endif
					    >{{ $item }}</option>
					@endforeach
				</select>
			</div>
		</div>

		<div class="form-group row {{ $errors->has('value') ? ' has-error' : '' }}">
			<label class="col-sm-7 control-label">@lang('field_value') <small class="form-text text-muted">@lang('field_value#descr')</small></label>
			<div class="col-sm-5">
				<input type="text" name="value" class="form-control" autocomplete="off"
                	value="{{ old('value', optional($setting)->value) }}" />
			</div>
		</div>

		<div class="form-group row {{ $errors->has('params') ? ' has-error' : '' }}">
			<label class="col-sm-7 control-label">@lang('field_params') <small class="form-text text-muted">@lang('field_params#descr')</small></label>
			<div class="col-sm-5">
				<!-- NO WRAP TEXTAREA CONTENT -->
				@if (optional($setting)->params)
					<textarea name="params" rows="4" class="form-control">{{ old('params', implode("\n", optional($setting)->params)) }}</textarea>
				@else
					<textarea name="params" rows="4" class="form-control">{{ old('params', optional($setting)->params) }}</textarea>
				@endif
			</div>
		</div>

		<div class="form-group row {{ $errors->has('class') ? ' has-error' : '' }}">
			<label class="col-sm-7 control-label">@lang('field_class') <small class="form-text text-muted">@lang('field_class#descr')</small></label>
			<div class="col-sm-5">
				<!-- NO WRAP TEXTAREA CONTENT -->
				<textarea name="class" rows="4" onkeydown="return !(event.keyCode == 13)" class="form-control">{{ old('class', optional($setting)->class) }}</textarea>
			</div>
		</div>

		<div class="form-group row {{ $errors->has('html_flags') ? ' has-error' : '' }}">
			<label class="col-sm-7 control-label">@lang('field_html_flags') <small class="form-text text-muted">@lang('field_html_flags#descr')</small></label>
			<div class="col-sm-5">
				<!-- NO WRAP TEXTAREA CONTENT -->
				<textarea name="html_flags" rows="4" onkeydown="return !(event.keyCode == 13)" class="form-control">{{ old('html_flags', optional($setting)->html_flags) }}</textarea>
			</div>
		</div>
	</div>
</div>

<div class="card card-default">
	<div class="card-header"><i class="fa fa-th-list"></i> @lang('legend.language')</div>
	<div class="card-body">

		<div class="form-group row {{ $errors->has('title') ? ' has-error' : '' }}">
			<label class="col-sm-7 control-label">@lang('field_title') <small class="form-text text-muted">@lang('field_title#descr')</small></label>
			<div class="col-sm-5">
				<input type="text" name="title" class="form-control" autocomplete="off"
                	value="{{ old('title', optional($setting)->title) }}" />
			</div>
		</div>

		<div class="form-group row {{ $errors->has('descr') ? ' has-error' : '' }}">
			<label class="col-sm-7 control-label">@lang('field_descr') <small class="form-text text-muted">@lang('field_descr#descr')</small></label>
			<div class="col-sm-5">
				<!-- NO WRAP TEXTAREA CONTENT -->
				<textarea name="descr" rows="4" onkeydown="return !(event.keyCode == 13)" class="form-control">{{ old('descr', optional($setting)->descr) }}</textarea>
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
					<span class="d-none d-md-inline">@lang(isset($setting->id) ? 'btn.save' : 'btn.create')</span>
				</button>
				<a href="{{ (! empty($setting->module_name) and \Route::has($route = 'admin.'.$setting->module_name.'.index')) ? route($route) : route('admin.dashboard') }}" class="btn btn-outline-dark btn-bg-white">
                    <span class="d-lg-none"><i class="fa fa-ban"></i></span>
                    <span class="d-none d-lg-inline">@lang('btn.cancel')</span>
                </a>
			</div>
		</div>
	</div>
</div>
