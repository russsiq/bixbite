<section class="action_page">
    <div class="action_page__inner">
        <header class="action_page__header">
            <h2 class="action_page__title">@lang('comments.edit_page')</h2>
        </header>

        <section class="action_page__content">
            <form action="{{ route('comments.update', $comment) }}" method="post">
                <div class="mb-3 row has-error">
                    <label for="password" class="col-md-3 col-form-label">@lang('comments.content')</label>
                    <div class="col-md-9">
                        <textarea name="content"
							rows="8"
							class="form-control{{ $errors->has('content') ? ' is-invalid' : '' }}"
                            required
							>{{ old('content', $comment->content) }}</textarea>
                        @if ($errors->has('content'))
                            <span class="invalid-feedback">{{ $errors->first('content') }}</span>
                        @endif
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-md-9 offset-md-3">
                        <input type="hidden" name="_method" value="PUT" />
                        <button type="submit" name="_token" value="{{ pageinfo('csrf_token') }}" class="btn btn-primary">@lang('common.btn.save')</button>
                        <a href="{{ $comment->url }}" class="btn btn-dark">@lang('common.btn.cancel')</a>
                    </div>
                </div>
            </form>
        </section>
    </div>
</section>
