<section class="action_page">
    <div class="action_page__inner">
        <header class="action_page__header">
            <h2 class="action_page__title">@lang('auth.register')</h2>
        </header>

        <section class="action_page__content">
            <form action="{{ route('register') }}" method="POST">
                <div class="mb-3 row">
                    <label for="name" class="col-md-4 col-form-label">@lang('auth.name')</label>
                    <div class="col-md-6">
                        <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required autofocus />
                        @error('name')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="email" class="col-md-4 col-form-label">@lang('auth.email')</label>
                    <div class="col-md-6">
                        <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required />
                        @error('email')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="password" class="col-md-4 col-form-label">@lang('auth.password')</label>
                    <div class="col-md-6">
                        <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password" required />
                        @error('password')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="password_confirmation" class="col-md-4 col-form-label">@lang('auth.password_confirmation')</label>
                    <div class="col-md-6">
                        <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" autocomplete="new-password" required />
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-md-6 offset-md-4">
                        <label><input type="checkbox" name="registration_rules" value="1" /> @lang('auth.registration_rules')</label>
                        @error('registration_rules')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" name="_token" value="{{ pageinfo('csrf_token') }}" class="btn btn-primary">@lang('auth.btn.register')</button>
                    </div>
                </div>
            </form>
        </section>
    </div>
</section>
