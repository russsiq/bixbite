<div class="input-group">
    <input type="number" name="g-recaptcha-response" value=""
        step="1" min="1000" max="9999" class="form-control" required />

    <span class="input-group-addon">
        <img id="img_captcha" src="{{ route('g_recaptcha.image_code', compact('noncache')) }}"
            alt="captcha" class="captcha" />
    </span>
</div>
