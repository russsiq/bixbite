<form id="feedback_form" action="{{ route('feedback.send') }}" method="post" role="form">
    <div class="form-group">
        <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="@lang('feedback.form.labels.name')" required />
        <i class="fa fa-user form-control-feedback"></i>
    </div>

    <div class="form-group">
        <input type="text" name="contact" value="{{ old('contact') }}" class="form-control" placeholder="@lang('feedback.form.labels.contact')" required />
        <i class="fa fa-phone form-control-feedback"></i>
    </div>

    <div class="form-group">
        <textarea name="content" rows="4" class="form-control" placeholder="@lang('feedback.form.labels.content')" required>{{ old('content') }}</textarea>
        <i class="fa fa-envelope-o form-control-feedback"></i>
    </div>

    <div class="form-group">
        <label class="d-block">@lang('feedback.form.labels.file')</label>
        <input type="file" name="file" accept="application/x-zip-compressed" />
        <i class="fa fa-file-archive-o form-control-feedback"></i>
    </div>

    <div class="form-group">
        <label class="control-label">
            <input type="checkbox" name="politics" value="1" required /> @lang('feedback.form.labels.politics')
        </label>
    </div>

    <div class="form-group-last">
        <input type="hidden" name="_token" value="{{ pageinfo('csrf_token') }}" />
        @g_recaptcha_input

        <button type="submit" class="btn btn-outline-primary">@lang('feedback.form.labels.submit')</button>
    </div>
</form>
