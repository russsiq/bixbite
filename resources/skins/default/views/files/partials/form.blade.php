<div class="card card-default">
	<div class="card-header"><i class="fa fa-th-list"></i> @lang('legend.general')</div>
	<div class="card-body">
		<div class="form-group row {{ $errors->has('title') ? ' has-error' : '' }}">
			<label class="col-sm-7 control-label">@lang('title') <sup class="text-danger">*</sup></label>
			<div class="col-sm-5">
				<input type="text" name="title" class="form-control" required
                	value="{{ old('title', optional($file)->title) }}" />
			</div>
		</div>

		<div class="form-group row {{ $errors->has('description') ? ' has-error' : '' }}">
			<label class="col-sm-7 control-label">@lang('description')</label>
			<div class="col-sm-5">
				<!-- NO WRAP TEXTAREA CONTENT -->
				<textarea name="description" rows="4" class="form-control" onkeydown="return !(event.keyCode == 13)">{{ old('description', optional($file)->description) }}</textarea>
			</div>
		</div>

		<div class="form-group row {{ $errors->has('attachment_id') ? ' has-error' : '' }}">
			<label class="col-sm-7 control-label">@lang('attaching') <sup class="text-danger">*</sup></label>
			<div class="col-sm-5">
				<input type="hidden" name="attachment_type" class="form-control" value="articles" />
				<select name="attachment_id" class="form-control">
					<option value=""></option>
					@foreach ($articles as $article)
						<option value="{{ $article->id }}" {{ (isset($file->attachment_id) and $file->attachment_id === $article->id) ? 'selected' : '' }}>{{ $article->title }}</option>
					@endforeach
				</select>
			</div>
		</div>

		<div class="form-group row{{ $errors->has('file') ? ' has-error' : '' }}">
			<label for="file" class="col-sm-7">@lang('file')</label>
			<div class="col-sm-5 baguetteBox text-center">
			@if (empty($file->id))
				<input type="file" name="file" class="form-control " />
			@elseif ('image' == $file->type)
				<a href="{{ $file->url }}" class="lightbox">
					<img src="{{ $file->getUrlAttribute('thumb') ?? $file->url }}" title="{{ $file->title }}" alt="{{ $file->name }}" />
				</a>
			@else
				<a href="{{ $file->url }}" target="_blank">{{ $file->url }}</a>
			@endif
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
    				<span class="d-none d-md-inline">@lang(isset($file->id) ? 'btn.save' : 'btn.create')</span>
    			</button>
    			<a href="{{ route('admin.files.index') }}" class="btn btn-outline-dark btn-bg-white">
                    <span class="d-lg-none"><i class="fa fa-ban"></i></span>
                    <span class="d-none d-lg-inline">@lang('btn.cancel')</span>
                </a>
    		</div>
		</div>
	</div>
</div>

@push('scripts')
	<script>
    baguetteBox.run('.baguetteBox', {
        noScrollbars: true,
        // buttons: false,
        captions: function(element) {
            return element.getElementsByTagName('img')[0].alt;
        }
    });
    </script>
@endpush
