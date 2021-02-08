@extends('layouts.app')

@section('title', __('Login'))

@section('header')
<h1>@lang('Login')</h1>
@endsection

@section('mainblock')
<section class="container mb-5">
    <div class="row">
        <div class="col col-md-5 col-xxxl-3 ml-auto">
            <div class="card shadow">
                <form action="{{ route('login') }}" method="post">
                    @csrf

                    <div class="card-body">
                        @if (session('status'))
                        <div class="alert alert-info  mb-3">
                            {{ session('status') }}
                        </div>
                        @endif

                        <div class="form-group mb-3">
                            <label for="email" class="form-label">@lang('Email')</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control"
                                autofocus required>
                            @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password" class="form-label">@lang('Password')</label>
                            <input id="password" type="password" name="password" value=""
                                class="form-control" autocomplete="current-password" required>
                            @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input id="remember_me" type="checkbox" name="remember" value="yes"
                                    class="form-check-input">
                                <label for="remember_me" class="form-check-label">
                                    @lang('Remember me')
                                </label>
                                @error('remember')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('password.request') }}" class="btn btn-outline-secondary">
                            @lang('Forgot your password?')
                        </a>
                        <button type="submit" class="btn btn-outline-success ml-auto">
                            @lang('Login')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
