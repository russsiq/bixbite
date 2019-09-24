<form id="feedback_form" action="{{ route('feedback.send') }}" method="post" role="form">
    <div class="form-group">
        <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Имя" required />
        <i class="fa fa-user form-control-feedback"></i>
    </div>

    <div class="form-group">
        <input type="text" name="contact" value="{{ old('contact') }}" class="form-control" placeholder="Email или телефон" required />
        <i class="fa fa-phone form-control-feedback"></i>
    </div>

    <div class="form-group">
        <textarea name="content" rows="4" class="form-control" placeholder="Текст сообщения" required>{{ old('content') }}</textarea>
        <i class="fa fa-envelope-o form-control-feedback"></i>
    </div>

    {{-- <div class="form-group">
        <label class="d-block">Прикрепить архив с дополнительной информацией:</label>
        <input type="file" name="file" accept="application/x-zip-compressed" />
        <i class="fa fa-file-archive-o form-control-feedback"></i>
    </div> --}}

    <div class="form-group">
        <label class="control-label">
            <input type="checkbox" name="politics" value="1" required /> Я согласен на обработку персональных данных
        </label>
    </div>

    <div class="form-group-last">
        <input type="hidden" name="_token" value="{{ pageinfo('csrf_token') }}" />
        <input name="g-recaptcha-response" type="hidden" value="" />

        <button type="submit" class="btn btn-outline-primary">Отправить сообщение</button>
    </div>
</form>
