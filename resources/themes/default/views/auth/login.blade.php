<section class="action_page">
    <div class="action_page__inner">
        <header class="action_page__header">
            <h2 class="action_page__title">@lang('auth.login')</h2>
        </header>

        <section class="action_page__content">
            <form action="{{ route('login') }}" method="post">
                <div class="form-group row">
                    @if ('name' == setting('users.login_username', 'name'))
                        <label for="name" class="col-md-4 col-form-label">@lang('auth.name')</label>
                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>
                            @if ($errors->has('name'))
                                <span class="invalid-feedback">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    @else
                        <label for="email" class="col-md-4 col-form-label">@lang('auth.email')</label>
                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
                            @if ($errors->has('email'))
                                <span class="invalid-feedback">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="form-group row has-error">
                    <label for="password" class="col-md-4 col-form-label">@lang('auth.password')</label>
                    <div class="col-md-6">
                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                        @if ($errors->has('password'))
                            <span class="invalid-feedback">{{ $errors->first('password') }}</span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6 offset-md-4">
                        <label><input type="checkbox" name="remember" /> @lang('auth.remember')</label>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" name="_token" value="{{ pageinfo('csrf_token') }}" class="btn btn-primary">@lang('auth.btn.login')</button>
                        <a href="{{ route('password.request') }}" class="btn btn-link pull-right">@lang('auth.forgot')</a>
                    </div>
                </div>
            </form>
        </section>
    </div>
</section>
