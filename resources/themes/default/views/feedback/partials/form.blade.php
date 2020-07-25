<form id="feedback_form" action="{{ route('feedback.send') }}" method="post" role="form">
    <div class="mb-3">
        <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="@lang('feedback.form.labels.name')" required />
        <i class="fa fa-user form-control-feedback"></i>
    </div>

    <div class="mb-3">
        <input type="text" name="contact" value="{{ old('contact') }}" class="form-control" placeholder="@lang('feedback.form.labels.contact')" required />
        <i class="fa fa-phone form-control-feedback"></i>
    </div>

    <div class="mb-3">
        <textarea name="content" rows="4" class="form-control" placeholder="@lang('feedback.form.labels.content')" required>{{ old('content') }}</textarea>
        <i class="fa fa-envelope-o form-control-feedback"></i>
    </div>

    {{-- <div class="mb-3">
        <label class="d-block">@lang('feedback.form.labels.file')</label>
        <input type="file" name="file" accept="application/x-zip-compressed" />
        <i class="fa fa-file-archive-o form-control-feedback"></i>
    </div> --}}

    <hr />

    <div class="mb-3">
        <label class="control-label">
            <input type="checkbox" name="politics" value="1" required /> @lang('feedback.form.labels.politics')
        </label>
    </div>

    @if (config('g_recaptcha.used'))
        <div class="mb-3 row">
            <div class="col-md-4">
                @g_recaptcha_input
            </div>
        </div>
    @endif

    <div class="mb-3-last row">
        <div class="col-md-4">
            <button type="submit" name="_token" value="{{ pageinfo('csrf_token') }}" class="btn btn-block btn-outline-primary">@lang('feedback.form.labels.submit')</button>
        </div>
    </div>
</form>
