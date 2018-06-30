<header class="page-header">
    <h2 class="section-title">@lang('auth.register_header')</h2>
</header>

<div class="article-container clearfix">

    <form action="{{ route('register') }}" method="POST">
        @csrf

        <div class="form-group row">
            <label for="name" class="col-md-4 col-form-label">@lang('auth.name')</label>
            <div class="col-md-6">
                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>
                @if ($errors->has('name'))
                    <span class="invalid-feedback">{{ $errors->first('name') }}</span>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label for="email" class="col-md-4 col-form-label">@lang('auth.email')</label>
            <div class="col-md-6">
                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>
                @if ($errors->has('email'))
                    <span class="invalid-feedback">{{ $errors->first('email') }}</span>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label for="password" class="col-md-4 col-form-label">@lang('auth.password')</label>
            <div class="col-md-6">
                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                @if ($errors->has('password'))
                    <span class="invalid-feedback">{{ $errors->first('password') }}</span>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label for="password-confirm" class="col-md-4 col-form-label">@lang('auth.password_confirmation')</label>
            <div class="col-md-6">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-8 offset-md-4">
                <label><input type="checkbox" name="registration_rules" value="1" /> @lang('auth.registration_rules')</label>
                @if ($errors->has('registration_rules'))
                    <span class="invalid-feedback d-block">{{ $errors->first('registration_rules') }}</span>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-8 offset-md-4">
                <input type="submit" value="@lang('auth.register')" class="btn btn-primary" />
            </div>
        </div>
    </form>
</div>
