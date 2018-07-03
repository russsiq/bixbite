<section class="comments">
    <div class="comments__inner">
        <div class="comments__header">
            <h4 class="comments__title">Комментарии</h4>
        </div>

        @if ($entity->comments_count)
            <ol class="comments__list">
                @each('comments.show', $entity->comments, 'comment')
            </ol>
        @endif

        <div id="respond" class="respond">
            <div class="respond__inner">
                <form id="respond__form" name="respond__form" action="{{ $entity->comment_store_action }}#respond" method="post">

                    @if (session('comment_add_success'))
                        @include('components.alert', ['type' => 'success', 'message' => trans('comments.msg.add_success')])
                    @endif

                    @if(! auth()->check() and setting('comments.regonly'))
                        <div class="alert alert-info">@lang('comments.msg.regonly')</div>
                    @elseif(! $entity->allow_com)
                        <div class="alert alert-info">@lang('comments.msg.disallow_com')</div>
                    @else
                        @guest
                        <div class="row">
                            <div class="col-md-4 ">
                                <div class="form-group">
                                    <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="@lang('auth.name')" class="form-control" required />
                                    @if ($errors->has('name'))<span class="invalid-feedback d-block">{{ $errors->first('name') }}</span>@endif
                                </div>
                            </div>
                            <div class="col-md-4 ">
                                <div class="form-group">
                                    <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="@lang('auth.email')" class="form-control" required />
                                    @if ($errors->has('email'))<span class="invalid-feedback d-block">{{ $errors->first('email') }}</span>@endif
                                </div>
                            </div>
                            @setting('system.captcha_used')
                                <div class="col-md-4">@captcha('components.partials.captcha')</div>
                            @endsetting
                        </div>
                        @endguest

                        <div class="form-group">
                            <textarea id="content" name="content" rows="8" placeholder="@lang('comments.content')" class="form-control" required>{{ old('content') }}</textarea>
                            @if ($errors->has('content'))<span class="invalid-feedback d-block">{{ $errors->first('content') }}</span>@endif
                        </div>
                        <p class="form-group">@lang('comments.msg.rules')</p>

                        <div class="form-group-last">
                            <input type="hidden" name="parent_id" value="{{ old('parent_id') }}" />
                            <button type="submit" name="_token" value="{{ pageinfo('csrf_token') }}" class="btn btn-primary">
                                @lang('comments.btn.post')
                            </button>
                            <button id="comment__reply_link-cancel" type="button" class="btn btn-primary" style="display:none;">
                                @lang('comments.btn.reply_cancel')
                            </button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</section>
