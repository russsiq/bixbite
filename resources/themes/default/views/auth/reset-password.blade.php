@extends('auth')

@section('title', __('Reset Password'))

@section('action_page')
<section class="action_page">
    <div class="action_page__inner">
        <header class="action_page__header">
            <h2 class="action_page__title">@lang('Reset Password')</h2>
        </header>

        <section class="action_page__content">
            @if (session('status'))
            <div class="alert alert-info mb-3">
                {{ session('status') }}
            </div>
            @endif

            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="mb-3 row">
                    <label for="email" class="col-md-4 col-form-label">@lang('Email')</label>
                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email', $request->email) }}" required>
                        @error('email')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="password" class="col-md-4 col-form-label">@lang('Password')</label>
                    <div class="col-md-6">
                        <input id="password" type="password"
                            class="form-control @error('password') is-invalid @enderror" name="password"
                            autocomplete="new-password" required>
                        @error('password')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="password_confirmation" class="col-md-4 col-form-label">@lang('Confirm Password')</label>
                    <div class="col-md-6">
                        <input id="password_confirmation" type="password" name="password_confirmation" value=""
                            class="form-control" autocomplete="new-password" required>
                        @error('password_confirmation')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">@lang('Reset Password')</button>
                    </div>
                </div>
            </form>
        </section>
    </div>
</section>
@endsection
