<section class="action_page">
    <div class="action_page__inner">
        <header class="action_page__header">
            <h2 class="action_page__title">@lang('auth.reset')</h2>
        </header>

        <section class="action_page__content">
            <form action="{{ route('password.update') }}" method="POST">
                <input type="hidden" name="token" value="{{ $token }}" />

                <div class="mb-3 row">
                    <label for="email" class="col-md-4 col-form-label">@lang('auth.email')</label>
                    <div class="col-md-6">
                        <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}" class="form-control @error('email') is-invalid @enderror" autocomplete="email" required autofocus />
                        @error('email')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="password" class="col-md-4 col-form-label">@lang('auth.password')</label>
                    <div class="col-md-6">
                        <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password" required>
                        @error('password')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="password-confirm" class="col-md-4 col-form-label">@lang('auth.password_confirmation')</label>
                    <div class="col-md-6">
                        <input id="password-confirm" type="password" name="password_confirmation" class="form-control" autocomplete="new-password" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" name="_token" value="{{ pageinfo('csrf_token') }}" class="btn btn-primary">@lang('auth.btn.reset')</button>
                    </div>
                </div>
            </form>
        </section>
    </div>
</section>
