@extends('auth')

@section('title', __('Reset Password'))

@section('action_page')
<section class="action_page">
    <div class="action_page__inner">
        <header class="action_page__header">
            <h2 class="action_page__title">@lang('Reset Password')</h2>
        </header>

        <section class="action_page__content">
            <p class="mb-3 text-muted">
                @lang('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.')
            </p>

            <form action="{{ route('password.email') }}" method="POST">
                @csrf

                <div class="mb-3 row">
                    <label for="email" class="col-md-4 col-form-label">@lang('Email')</label>
                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">@lang('Send Password Reset Link')</button>
                    </div>
                </div>
            </form>
        </section>
    </div>
</section>
@endsection
