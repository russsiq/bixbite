@extends('auth')

@section('title', __('Login'))

@section('action_page')
<section class="action_page">
    <div class="action_page__inner">
        <header class="action_page__header">
            <h2 class="action_page__title">@lang('Login')</h2>
        </header>

        <section class="action_page__content">
            <form action="{{ route('login') }}" method="POST">
                @csrf

                <div class="mb-3 row">
                    @if ('name' === config('fortify.username'))
                        <label for="name" class="col-md-4 col-form-label">
                            @lang('Name')
                        </label>
                        <div class="col-md-6">
                            <input id="name" type="text" name="name" value="{{ old('name') }}"
                                class="form-control" required />
                            @error('name')
                                <span class=" invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                    @else
                        <label for="email" class="col-md-4 col-form-label">
                            @lang('Email')
                        </label>
                        <div class="col-md-6">
                            <input id="email" type="email" name="email" value="{{ old('email') }}"
                                class="form-control" required />
                            @error('email')
                                <span class=" invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif
                </div>

                <div class="mb-3 row">
                    <label for="password" class="col-md-4 col-form-label">
                        @lang('Password')
                    </label>
                    <div class="col-md-6">
                        <input id="password" type="password" name="password" value=""
                            class="form-control" autocomplete="current-password" required />
                        @error('password')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-md-6 offset-md-4">
                        <label>
                            <input type="checkbox" name="remember" /> @lang('Remember me')
                        </label>
                        @error('remember')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            @lang('Login')
                        </button>
                        <a href="{{ route('password.request') }}" class="btn btn-link pull-right">
                            @lang('Forgot your password?')
                        </a>
                    </div>
                </div>
            </form>
        </section>
    </div>
</section>
@endsection
