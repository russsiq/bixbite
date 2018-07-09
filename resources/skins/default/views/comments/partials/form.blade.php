<div class="card card-default">
	<div class="card-header"><i class="fa fa-th-list"></i> @lang('legend.general')</div>
	<div class="card-body">
		<div class="form-group row {{ $errors->has('content') ? ' has-error' : '' }}">
			<label class="col-sm-7 control-label">@lang('content')<small class="form-text text-muted">@lang('content#descr')</small></label>
			<div class="col-sm-5">
				<!-- NO WRAP TEXTAREA CONTENT -->
				<textarea name="content" rows="4" class="form-control">{{ old('content', optional($comment)->content) }}</textarea>
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
    				<span class="d-none d-md-inline">@lang(isset($comment->id) ? 'btn.save' : 'btn.create')</span>
    			</button>
    			<a href="{{ route('admin.comments.index') }}" class="btn btn-outline-dark btn-bg-white">
                    <span class="d-lg-none"><i class="fa fa-ban"></i></span>
                    <span class="d-none d-lg-inline">@lang('btn.cancel')</span>
                </a>
    		</div>
		</div>
	</div>
</div>
