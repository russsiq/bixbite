<div class="card card-default">
	<div class="card-header"><i class="fa fa-th-list"></i> @lang('legend.general')</div>
	<div class="card-body">

		<div class="form-group row {{ $errors->has('title') ? ' has-error' : '' }}">
			<label class="col-sm-7 control-label">@lang('title') <sup class="text-danger">*</sup></label>
			<div class="col-sm-5">
				<input type="text" name="title" class="form-control" required autofocus
                	value="{{ old('title', optional($note)->title) }}" />
			</div>
		</div>

		<div class="form-group row {{ $errors->has('description') ? ' has-error' : '' }}">
			<label class="col-sm-7 control-label">@lang('description')<small class="form-text text-muted">@lang('description#descr')</small></label>
			<div class="col-sm-5">
				<!-- NO WRAP TEXTAREA CONTENT -->
				<textarea name="description" rows="4" class="form-control" onkeydown="return !(event.keyCode == 13)">{{ old('description', optional($note)->description) }}</textarea>
			</div>
		</div>
	</div>
</div>

<!-- SUBMIT Form -->
<div class="card">
	<div class="card-footer">
		<div class="row">
    		<div class="col-sm-5 offset-md-7">
    			<button type="submit" title="Ctrl+S" class="btn btn-outline-success btn-bg-white">
    				<span class="d-md-none"><i class="fa fa-floppy-o"></i></span>
    				<span class="d-none d-md-inline">@lang(isset($note->id) ? 'btn.save' : 'btn.create')</span>
    			</button>
    			<a href="{{ route('admin.notes.index') }}" class="btn btn-outline-dark btn-bg-white">
                    <span class="d-lg-none"><i class="fa fa-ban"></i></span>
                    <span class="d-none d-lg-inline">@lang('btn.cancel')</span>
                </a>
    		</div>
		</div>
	</div>
</div>
