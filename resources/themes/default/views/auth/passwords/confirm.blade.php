<section class="action_page">
    <div class="action_page__inner">
        <header class="action_page__header">
            <h2 class="action_page__title">@lang('auth.confirm_password')</h2>
        </header>

        <section class="action_page__content">
            <p>@lang('auth.confirm_before')</p>

            <form action="{{ route('password.confirm') }}" method="POST">
                <div class="form-group row">
                    <label for="password" class="col-md-4 col-form-label">@lang('auth.password')</label>
                    <div class="col-md-6">
                        <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="current-password" required />
                        @error('password')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" name="_token" value="{{ pageinfo('csrf_token') }}" class="btn btn-primary">@lang('auth.btn.confirm')</button>
                        <a href="{{ route('password.request') }}" class="btn btn-link pull-right">@lang('auth.forgot')</a>
                    </div>
                </div>
            </form>
        </section>
    </div>
</section>
