<div class="row">
	<div class="col-sm-12 col-lg-8">
		<!-- MAIN CONTENT -->
		{{-- <div class="card-header"><i class="fa fa-th-list text-muted"></i> @lang('options.main_content')</div> --}}
		<div class="form-group">
			{{-- :delete_url="'{{ route('admin.files.delete', $file) }}'"  --}}
			<div class="row">
				<div class="col-sm-12 col-md-5 col-lg-4 mb-2">
					<image-uploader
				        :input_name="'image_id'"
						:input_value="{{ old('image_id', 0) }}"
						:base_url="'{{ route('admin.files.index') }}'"
				        :uploaded="{{ ! empty($article->image_id) ? $article->image->toJson() : '{}' }}"
	                	:attachment_id="'{{ $article->id ?? null }}'"
	                    :attachment_type="'articles'"
					></image-uploader>
				</div>

				<div class="col-sm-12 col-md-7 col-lg-8 mb-2">
					<div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
						<input type="text" name="title"
							value="{{ old('title', optional($article)->title) }}"
							maxlength="255"
							class="form-control"
							placeholder="@lang('title#tip') ..."
							autocomplete="off" required />
					</div>
					@if (setting('articles.manual_slug', false))
						<div class="form-group {{ $errors->has('slug') ? ' has-error' : '' }}">
							<input type="text" name="slug"
								value="{{ old('slug', optional($article)->slug) }}"
								maxlength="255"
								class="form-control"
								placeholder="@lang('slug#tip') ..." autocomplete="off" />
						</div>
					@endif
					<div class="form-group {{ $errors->has('teaser') ? ' has-error' : '' }} mb-0">
						<textarea name="teaser"
							rows="4"
							maxlength="255"
							class="form-control noresize"
							placeholder="@lang('teaser#tip') ..."
							>{{ old('teaser', optional($article)->teaser) }}</textarea>
					</div>
				</div>
			</div>
		</div>

		<div class="form-group {{ $errors->has('content') ? ' has-error' : '' }}">
			<quill-editor :article-id="article.id" lang="{{ app_locale() }}"></quill-editor>
			<textarea name="content" style="width:100%;display:none;" rows="8"
				>{!! old('content', optional($article)->getOriginal('content')) !!}</textarea>
		</div>

		<div id="accordion">
			{{-- <div class="card mb-0">
				<div class="card-header">
					<a href="#card_files" data-toggle="collapse" data-target="#card_files" class="d-block"><i class="fa fa-files-o text-muted"></i> @lang('options.files')</a>
				</div>
				<div id="card_files" class="collapse" data-parent="#accordion">
					<div class="card-body">
						<div class="form-group row">
							<files-attaching :attachment_id="'{{ $article->id }}'" :attachment_type="'{{ $article->getMorphClass() }}'" :multiple="true"
								:upload_url="'{{ route('admin.files.upload') }}'"
								:update_url="'{{ route('admin.files.upload', ':id') }}'"
								:delete_url="'{{ route('admin.files.delete', ':id') }}'"></files-attaching>
						</div>
					</div>
				</div>
			</div> --}}

			@if (count($x_fields))
				<div class="card mb-0">
					<div class="card-header">
	                    <a href="#card_x_fields" data-toggle="collapse" data-target="#card_x_fields" class="d-block"><i class="fa fa-table text-muted"></i> @lang('options.x_fields')</a>
					</div>
					<div id="card_x_fields" class="collapse" data-parent="#accordion">
						<div class="card-body">
							@foreach ($x_fields as $x_field)
								{{-- DON'T use "@each(...)", because "$loop->..." and "$article->..." does not work --}}
								@include('articles.partials.x_fields', ['x_field' => $x_field, 'item' => $article])
							@endforeach
						</div>
					</div>
				</div>
			@endif

			@if (setting('articles.manual_meta', true))
				<div class="card mb-0">
					<div class="card-header">
	                    <a href="#card_meta" data-toggle="collapse" data-target="#card_meta" class="d-block"><i class="fa fa-header text-muted"></i> @lang('options.meta')</a>
					</div>
					<div id="card_meta" class="collapse" data-parent="#accordion">
						<div class="card-body">
							<div class="form-group has-float-label{{ $errors->has('description') ? ' has-error' : '' }}">
								<label>@lang('description')</label>
								<textarea name="description"
									rows="3"
									maxlength="255"
									class="form-control"
									>{{ old('description', optional($article)->description) }}</textarea>
							</div>
							<div class="form-group has-float-label{{ $errors->has('keywords') ? ' has-error' : '' }}">
								<label>@lang('keywords')</label>
								<input type="text" name="keywords"
									maxlength="255"
									value="{{ old('keywords', optional($article)->keywords) }}"
									class="form-control"
									autocomplete="off" />
							</div>
							<div class="form-group has-float-label{{ $errors->has('robots') ? ' has-error' : '' }}">
								<label>@lang('robots')</label>
								<select name="robots" class="form-control">
									<option value="" @if(empty($article->robots)) selected @endif>@lang('default')</option>
									<option value="noindex" @if(optional($article)->robots == 'noindex') selected @endif>noindex</option>
									<option value="nofollow" @if(optional($article)->robots == 'nofollow') selected @endif>nofollow</option>
									<option value="none" @if(optional($article)->robots == 'none') selected @endif>none</option>
								</select>
							</div>
						</div>
					</div>
				</div>
			@endif

			<div class="card">
				<div class="card-header">
                    <a href="#card_tags" data-toggle="collapse" data-target="#card_tags" class="d-block"><i class="fa fa-tags text-muted"></i> @lang('options.tags')</a>
				</div>
				<div id="card_tags" class="collapse" data-parent="#accordion" aria-expanded="false">
					<div class="card-body">
						<div class="form-group has-float-label{{ $errors->has('tags') ? ' has-error' : '' }}">
							<label>@lang('tags')</label>
							<input type="text" name="tags"
								maxlength="255"
								value="{{ old('tags', $tags) }}"
								class="form-control"
								autocomplete="off" />
						</div>
					</div>
				</div>
			</div>

			<!-- ATTACHES -->
			{{-- <div class="card card-table">
				<div class="card-header">
					<a href="#card_attaches" data-toggle="collapse" data-target="#card_attaches" class="d-block"><i class="fa fa-files-o text-muted"></i> @lang('options.files')</a>
				</div>
				<div id="card_attaches" class="collapse" data-parent="#accordion" aria-expanded="false">
					<div class="card-body">
						<table id="attachFilelist" class="table table-sm">
							<thead>
								<tr>
									<th>@lang('filename') - @lang('filesize')</th>
									<th class="text-center" width="10">@lang('action')</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td></td>
									<td class="text-center" width="10">
										<button type="button" title="@lang('add_rows')" onclick="attachAddRow('attachFilelist');" class="btn btn-outline-primary"><i class="fa fa-plus"></i></button>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div> --}}
		</div>

		<!-- SUBMIT Form -->
		<div class="card">
		    <div class="card-footer">
        		<div class="row">
					<div class="col col-xs-6{{ $errors->has('state') ? ' has-error' : '' }}">
						<select name="state" class="form-control">
							<option value="published" @if(isset($article->state) and $article->state == 'published') selected @endif>@lang('action.published')</option>
							<option value="unpublished" @if(isset($article->state) and $article->state == 'unpublished') selected @endif>@lang('action.unpublished')</option>
							<option value="draft" @if(isset($article->state) and $article->state == 'draft') selected @endif>@lang('action.draft')</option>
						</select>
					</div>
		    		<div class="col col-xs-6 text-right">
		    			<button type="submit" title="Ctrl+S" class="btn btn-outline-success btn-bg-white">
		    				<span class="d-md-none"><i class="fa fa-floppy-o"></i></span>
		    				<span class="d-none d-md-inline">@lang(isset($article->id) ? 'btn.save' : 'btn.create')</span>
		    			</button>
		    		</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Right edit column -->
	<div id="rightBar" class="col-sm-12 col-lg-4">
		@isset($article->id)
			<div class="card card-default card-table">
				<div class="card-header">@lang('legend.common_info')</div>
				<div class="card-body table-responsive">
					<table class="table table-sm">
						<tbody>
							<tr><td>@lang('author')</td><td><b>{{ $article->user->name }}</b> [{{ $article->user->id }}]</td></tr>
							<tr><td>@lang('state')</td>
								<td><b>
									@if($article->state == 'published')
										<a href="{{ $article->url }}" target="_blank">
											<span class="text-success"><i class="fa fa-external-link"></i> @lang('state.published')</span>
										</a>
									@elseif($article->state == 'unpublished') <span class="text-warning">@lang('state.unpublished')</span>
									@else <span class="text-danger">@lang('state.draft')</span>
	                                @endif
								</b></td>
	                        </tr>
							<tr><td>@lang('created_at')</td><td>{{ $article->created_at }}</td></tr>
							<tr><td>@lang('updated_at')</td><td>{{ $article->updated_at ?: __('not_updated') }}</td></tr>
							{{-- <tr><td>@lang('published_at')</td><td>{{ $article->published_at ?: __('not_published') }}</td></tr>
							<tr><td>@lang('deleted_at')</td><td>{{ $article->deleted_at ?: __('not_deleted') }}</td></tr> --}}
						</tbody>
					</table>
				</div>
			</div>
		@endisset

		<div class="card card-default">
			<div class="card-header">@lang('category')</div>
			<div class="card-body{{ $errors->has('categories') ? ' has-error' : '' }}">
				<select id="catmenu" name="categories[]" class="form-control" multiple>
					{{-- DON'T use "@each(...)", because "$loop->..." does not work --}}
					@include('articles.partials.categories_items', ['items' => $categories_items])
				</select>
			</div>
		</div>
		<div class="card card-default">
			<div class="card-header">@lang('options.publish')</div>
			<div class="card-body">
				<label class="control-label"><input type="checkbox" name="on_mainpage" value="1"
					@if(old('on_mainpage', optional($article)->on_mainpage) == 1 or empty($article)) checked @endif />&nbsp;@lang('on_mainpage')</label>
				<label class="control-label"><input type="checkbox" name="is_pinned" value="1"
					@if(old('is_pinned', optional($article)->is_pinned) == 1) checked @endif />&nbsp;@lang('is_pinned')</label>
				<label class="control-label"><input type="checkbox" name="is_catpinned" value="1"
					@if(old('is_catpinned', optional($article)->is_catpinned) == 1) checked @endif />&nbsp;@lang('is_catpinned')</label>
				<label class="control-label"><input type="checkbox" name="is_favorite" value="1"
					@if(old('is_favorite', optional($article)->is_favorite) == 1) checked @endif />&nbsp;@lang('is_favorite')</label>
				{{-- <label class="control-label"><input type="checkbox" name="flag_html" value="1"
					@if(old('flag_html', optional($article)->flag_html) == 1) checked @endif />&nbsp;@lang('flag_html')</label>
				<label class="control-label"><input type="checkbox" name="flag_raw" value="1"
					@if(old('flag_raw', optional($article)->flag_raw) == 1) checked @endif />&nbsp;@lang('flag_raw')</label> --}}
				<label class="control-label">
					<input id="currdate" type="checkbox" name="currdate" value="1" />&nbsp;@lang('currdate')</label>
			</div>
		</div>
		<div class="card card-default">
			<div class="card-header">@lang('options.datetime')</div>
			<div class="card-body">
				<div class="input-group{{ $errors->has('created_at') ? ' has-error' : '' }}">
					<div class="input-group-prepend">
                        <div class="input-group-text">
                            <input id="customdate" type="checkbox" name="customdate" value="1" />
                        </div>
                    </div>
					<input id="created_at" type="datetime-local" name="created_at" value="{{ old('created_at', '') }}" class="form-control" />
				</div>
			</div>
		</div>
		<div class="card card-default">
			<div class="card-header">@lang('options.commenting')</div>
			<div class="card-body{{ $errors->has('allow_com') ? ' has-error' : '' }}">
				<select name="allow_com" class="form-control">
					<option value="2" @if(old('allow_com', optional($article)->allow_com) == 2) selected @endif>@lang('default')</option>
					<option value="1" @if(old('allow_com', optional($article)->allow_com) == 1) selected @endif>@lang('allow')</option>
					<option value="0" @if(old('allow_com', optional($article)->allow_com) == 0 and !empty($article)) selected @endif>@lang('disallow')</option>
				</select>
			</div>
		</div>
	</div>
</div>
