@extends('auth')

@section('title', __('Register'))

@section('action_page')
<section class="action_page">
    <div class="action_page__inner">
        <header class="action_page__header">
            <h2 class="action_page__title">@lang('Register')</h2>
        </header>

        <section class="action_page__content">
            <form action="{{ route('register') }}" method="POST">
                @csrf

                <div class="mb-3 row">
                    <label for="name" class="col-md-4 col-form-label">@lang('Name')</label>
                    <div class="col-md-6">
                        <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required autofocus />
                        @error('name')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="email" class="col-md-4 col-form-label">@lang('Email')</label>
                    <div class="col-md-6">
                        <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required />
                        @error('email')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="password" class="col-md-4 col-form-label">@lang('Password')</label>
                    <div class="col-md-6">
                        <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password" required />
                        @error('password')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="password_confirmation" class="col-md-4 col-form-label">@lang('Confirm Password')</label>
                    <div class="col-md-6">
                        <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" autocomplete="new-password" required />
                        @error('password_confirmation')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-md-6 offset-md-4">
                        <div class="form-check">
                            <input id="terms" type="checkbox" name="terms" value="yes" class="form-check-input">
                            <label for="terms" class="form-check-label">
                                @lang('I agree to the :terms_of_service and :privacy_policy', [
                                'terms_of_service' => '<a href="#">'.__('Terms of Service').'</a>',
                                'privacy_policy' => '<a href="#">'.__('Privacy Policy').'</a>',
                                ])
                            </label>
                            @error('terms')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">@lang('Register')</button>
                        <a href="{{ route('login') }}" class="btn btn-link pull-right">@lang('Already registered?')</a>
                    </div>
                </div>
            </form>
        </section>
    </div>
</section>
@endsection
