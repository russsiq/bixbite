{{-- Атрибуты `id` используются JavaScript. --}}
<div id="respond" class="respond">
    <div class="respond__inner">
        @if (! $commentable->allow_com)
            {{-- Если Комментарии к записи отключены. --}}
            <div class="alert alert-info">@lang('comments.msg.disallow_com')</div>
        @elseif(auth()->guest() and setting('comments.regonly', true))
            {{-- Если комментарии Только для зарегистрированных. --}}
            <div class="alert alert-info">@lang('comments.msg.regonly')</div>
        @else
            <form id="respond_form" action="{{ $commentable->comment_store_url }}#respond" method="post">
                @csrf
                <input type="hidden" name="parent_id" value="{{ old('parent_id') }}" />

                <div class="row">
                    @guest
                        <div class="col-md-4 ">
                            <div class="mb-3">
                                <input type="text" name="author_name" value="{{ old('author_name') }}" class="form-control" placeholder="@lang('auth.name')" required />
                                @if ($errors->has('author_name'))
                                    <span class="invalid-feedback d-block">{{ $errors->first('author_name') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4 ">
                            <div class="mb-3">
                                <input type="email" name="author_email" value="{{ old('author_email') }}" class="form-control" placeholder="@lang('auth.email')" required />
                                @if ($errors->has('author_email'))
                                    <span class="invalid-feedback d-block">{{ $errors->first('author_email') }}</span>
                                @endif
                            </div>
                        </div>
                    @endguest

                    <div class="col-md-4">
                        <div class="mb-3">
                        {{-- @setting('system.captcha_used')
                            @captcha('components.partials.captcha')
                        @endsetting --}}
                        @if (config('g_recaptcha.used'))
                            @g_recaptcha_input
                        @endif
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <textarea name="content" rows="8" class="form-control" placeholder="@lang('comments.content')" required>{{ old('content') }}</textarea>
                    @if ($errors->has('content'))
                        <span class="invalid-feedback d-block">{{ $errors->first('content') }}</span>
                    @endif
                </div>

                <div class="mb-3">@lang('comments.msg.rules')</div>

                <div class="mb-3-last">
                    <button type="submit" class="btn btn-outline-dark">@lang('comments.btn.post')</button>

                    {{-- Атрибуты `id` и `data-reply` используются JavaScript. --}}
                    <button id="cancel_reply" type="button" class="btn btn-outline-dark d-none" data-reply>@lang('comments.btn.reply_cancel')</button>
                </div>
            </form>
        @endif
    </div>
</div>
