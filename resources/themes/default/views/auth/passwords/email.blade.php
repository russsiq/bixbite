<header class="page-header">
    <h2 class="section-title">@lang('auth.reset')</h2>
</header>

<div class="article-container clearfix">
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form action="{{ route('password.email') }}" method="POST">
        @csrf

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
            <div class="col-md-6 offset-md-4">
                <input type="submit" value="@lang('auth.send')" class="btn btn-primary" />
            </div>
        </div>
    </form>
</div>
