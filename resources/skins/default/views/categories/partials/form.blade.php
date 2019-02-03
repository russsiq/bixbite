<div class="card card-default">
	<div class="card-header"><i class="fa fa-th-list"></i> @lang('legend.general')</div>
	<div class="card-body">

		<div class="form-group row {{ $errors->has('show_in_menu') ? ' has-error' : '' }}">
			<label class="col-sm-7 control-label">@lang('show_in_menu')</label>
			<div class="col-sm-5">
				<select name="show_in_menu" class="form-control">
					@foreach ([__('no'), __('yes')] as $key => $value)
						<option value="{{ $key }}" {{ old('show_in_menu', optional($category)->show_in_menu) == $key ? 'selected' : '' }}>{{ __($value) }}</option>
					@endforeach
				</select>
			</div>
		</div>

		<div class="form-group row {{ $errors->has('title') ? ' has-error' : '' }}">
			<label class="col-sm-7 control-label">@lang('title') <sup class="text-danger">*</sup></label>
			<div class="col-sm-5">
				<input type="text" name="title" class="form-control" required
                	value="{{ old('title', optional($category)->title) }}" />
				{{--@if ($errors->has('title'))
					<small class="form-text text-muted">{{ $errors->first('title') }}</small>
					<i class="fa fa-times form-control-feedback"></i>
				@endif--}}
			</div>
		</div>

		<div class="form-group row {{ $errors->has('slug') ? ' has-error' : '' }}">
			<label class="col-sm-7 control-label">@lang('slug')<small class="form-text text-muted">@lang('slug#descr')</small></label>
			<div class="col-sm-5">
				<input type="text" name="slug" class="form-control"
					value="{{ old('slug', optional($category)->slug) }}" />
			</div>
		</div>

		<div class="form-group row {{ $errors->has('alt_url') ? ' has-error' : '' }}">
			<label class="col-sm-7 control-label">@lang('alt_url')
				<small class="form-text text-muted">@lang('alt_url#descr')</small>
			</label>
			<div class="col-sm-5">
				<input type="text" name="alt_url" class="form-control"
					value="{{ old('alt_url', optional($category)->alt_url) }}" />
			</div>
		</div>
	</div>
</div>

<div class="card card-default">
	<div class="card-header"><i class="fa fa-th-list"></i> @lang('legend.indexpage')</div>
	<div class="card-body">

		<div class="form-group row{{ $errors->has('img') ? ' has-error' : '' }}">
			<label class="col-sm-7 control-label">@lang('image')
				<small class="form-text text-muted">@lang('image#descr')</small>
			</label>
			<div class="col-sm-5">
                <image-uploader
                    :input_name="'image_id'"
                    :post_url="'{{ route('admin.files.upload') }}'"
                    @if (! empty($category->image_id) and optional($category->image)->url)
                        :url="'{{ $category->image->getUrlAttribute('thumb') }}'"
                    @endif
                    @if (old('image_id'))
                        :fetch_url="'{{ route('admin.files.show', ['id' => old('image_id')]) }}'"
                    @endif
                ></image-uploader>
			</div>
		</div>

		<div class="form-group row {{ $errors->has('info') ? ' has-error' : '' }}">
			<label class="col-sm-7 control-label">@lang('info')<small class="form-text text-muted">@lang('info#descr')</small></label>
			<div class="col-sm-5">
				<!-- NO WRAP TEXTAREA CONTENT -->
				<textarea id="info" name="info" rows="4" class="form-control" onkeydown="return !(event.keyCode == 13)">{{ old('info', optional($category)->info) }}</textarea>
			</div>
		</div>

		<div class="form-group row {{ $errors->has('description') ? ' has-error' : '' }}">
			<label class="col-sm-7 control-label">@lang('description')<small class="form-text text-muted">@lang('description#descr')</small></label>
			<div class="col-sm-5">
				<textarea id="description" name="description" rows="4" class="form-control" onkeydown="return !(event.keyCode == 13)">{{ old('description', optional($category)->description) }}</textarea>
			</div>
		</div>

		<div class="form-group row {{ $errors->has('keywords') ? ' has-error' : '' }}">
			<label class="col-sm-7 control-label">@lang('keywords')<small class="form-text text-muted">@lang('keywords#descr')</small></label>
			<div class="col-sm-5">
				<input type="text" name="keywords" class="form-control"
					value="{{ old('keywords', optional($category)->keywords) }}" />
			</div>
		</div>
	</div>
</div>

<div class="card card-default">
	<div class="card-header"><i class="fa fa-th-list"></i> @lang('legend.display')</div>
	<div class="card-body">
		<div class="form-group row {{ $errors->has('paginate') ? ' has-error' : '' }}">
			<label class="col-sm-7 control-label">@lang('paginate')<small class="form-text text-muted">@lang('paginate#descr')</small></label>
			<div class="col-sm-5">
				<input type="number" name="paginate" class="form-control"
					value="{{ old('paginate', optional($category)->paginate) }}" />
			</div>
		</div>

		<div class="form-group row {{ $errors->has('order_by') ? ' has-error' : '' }}">
			<label class="col-sm-7 control-label">@lang('order_by')</label>
			<div class="col-sm-5">
                <select name="order_by" class="form-control">
					@foreach ([
                            'id' => 'order_by.id',
                            'title' => 'order_by.title',
                            'created_at' => 'order_by.created_at',
                            'updated_at' => 'order_by.updated_at',
                            'votes' => 'order_by.votes',
                            'rating' => 'order_by.rating',
                            'views' => 'order_by.views',
                            'comments_count' => 'order_by.comments_count',
                        ] as $key => $value)
						<option value="{{ $key }}" {{ old('order_by', optional($category)->order_by) == $key ? 'selected' : '' }}>{{ __($value) }}</option>
					@endforeach
				</select>
			</div>
		</div>

		<div class="form-group row {{ $errors->has('direction') ? ' has-error' : '' }}">
			<label class="col-sm-7 control-label">@lang('direction')</label>
			<div class="col-sm-5">
				<select name="direction" class="form-control">
					@foreach ([
				        'desc' => 'direction.desc',
				        'asc' => 'direction.asc',
				    ] as $key => $value)
						<option value="{{ $key }}" {{ old('direction', optional($category)->direction) == $key ? 'selected' : '' }}>{{ __($value) }}</option>
					@endforeach
				</select>
			</div>
		</div>

		<div class="form-group row {{ $errors->has('template') ? ' has-error' : '' }}">
			<label class="col-sm-7 control-label">@lang('template')
				<small class="form-text text-muted">@lang('template#descr')</small>
            </label>
			<div class="col-sm-5">
				<select name="template" class="form-control">
					@foreach ($template_list as $key => $value)
						<option value="{{ $key }}" {{ old('template', optional($category)->template) == $key ? 'selected' : '' }}>{{ $value }}</option>
					@endforeach
				</select>
			</div>
		</div>
	</div>
</div>

@if (count($x_fields))
<div class="card card-default">
	<div class="card-header"><i class="fa fa-th-list"></i> @lang('options.x_fields')</div>
	<div class="card-body">
	@foreach ($x_fields as $x_field)
		{{-- DON'T use "@each(...)", because "$loop->..." and "$category->..." does not work --}}
		@include('categories.partials.x_fields', ['x_field' => $x_field, 'item' => $category])
	@endforeach
	</div>
</div>
@endif

<!-- SUBMIT Form -->
<div class="card">
	<div class="card-footer">
		<div class="row">
    		<div class="col-sm-5 offset-md-7">
    			<button type="submit" title="Ctrl+S" class="btn btn-outline-success btn-bg-white">
    				<span class="d-md-none"><i class="fa fa-floppy-o"></i></span>
    				<span class="d-none d-md-inline">@lang(isset($category->id) ? 'btn.save' : 'btn.create')</span>
    			</button>
    			<a href="{{ route('admin.categories.index') }}" class="btn btn-outline-dark btn-bg-white">
                    <span class="d-lg-none"><i class="fa fa-ban"></i></span>
                    <span class="d-none d-lg-inline">@lang('btn.cancel')</span>
                </a>
    		</div>
		</div>
	</div>
</div>
