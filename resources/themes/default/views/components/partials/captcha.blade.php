<div class="input-group">
    <input id="captcha" type="number" name="captcha" value="" class="form-control" placeholder="@lang('auth.captcha')" step="1" min="1000" max="9999" required />
    <span class="input-group-addon p-0"><img id="img_captcha" src="{{ route('captcha.url') }}?rand={{ $captcha_rand }}" alt="captcha" class="captcha" /></span>
</div>
@if ($errors->has('captcha'))<span class="invalid-feedback d-block">{{ $errors->first('captcha') }}</span>@endif
