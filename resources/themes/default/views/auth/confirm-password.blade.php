@extends('auth')

@section('title', __('Confirm Password'))

@section('action_page')
<section class="action_page">
    <div class="action_page__inner">
        <header class="action_page__header">
            <h2 class="action_page__title">@lang('Confirm Password')</h2>
        </header>

        <section class="action_page__content">
            <p class="mb-3 text-muted">
                @lang('This is a secure area of the application. Please confirm your password before continuing.')
            </p>

            <form action="{{ route('password.confirm') }}" method="POST">
                @csrf

                <div class="mb-3 row">
                    <label for="password" class="col-md-4 col-form-label">@lang('Password')</label>
                    <div class="col-md-6">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="current-password" required>
                        @error('password')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">@lang('Confirm')</button>
                    </div>
                </div>
            </form>
        </section>
    </div>
</section>
@endsection
