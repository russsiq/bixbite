<header class="page-header">
    <h2 class="section-title">@lang('auth.login_header')</h2>
</header>

<div class="article-container clearfix">
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group row">
            @if ('name' == setting('users.login_username', 'name'))
                <label for="name" class="col-sm-4 col-form-label">@lang('auth.name')</label>
                <div class="col-md-6">
                    <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>
                    @if ($errors->has('name'))
                        <span class="invalid-feedback">{{ $errors->first('name') }}</span>
                    @endif
                </div>
            @else
                <label for="email" class="col-sm-4 col-form-label">@lang('auth.email')</label>
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
                <label><input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} /> @lang('auth.remember')</label>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-6 offset-md-4">
                <input type="submit" value="@lang('auth.login')" class="btn btn-primary" />
                <a class="btn btn-link pull-right" href="{{ route('password.request') }}">@lang('auth.forgot')</a>
            </div>
        </div>
    </form>
</div>
