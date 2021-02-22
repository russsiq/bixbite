@extends('layouts.app')

@section('title', __('Login'))

@section('header')
<h1 class="h3 text-center">@lang('Login')</h1>
@endsection

@section('mainblock')
<section class="container mb-5">
    <div class="row justify-content-center">
        <div class="col col-md-5 col-xxxl-3">

            @if (session('status'))
            <div class="alert alert-info mb-3">
                {{ session('status') }}
            </div>
            @endif

            <div class="card shadow">
                <form action="{{ route('login') }}" method="post">
                    @csrf

                    <div class="card-body">
                        @if ('name' === config('fortify.username'))
                            <div class="form-group mb-3">
                                <label for="name" class="form-label">@lang('Name')</label>
                                <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-control"
                                    autofocus required>
                                @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        @else
                            <div class="form-group mb-3">
                                <label for="email" class="form-label">@lang('Email')</label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control"
                                    autofocus required>
                                @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        <div class="form-group mb-3">
                            <label for="password" class="form-label d-block">
                                @lang('Password')
                                <a href="{{ route('password.request') }}" class="float-end">
                                    <small class="fw-bold">@lang('Forgot your password?')</small>
                                </a>
                            </label>
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

                    <div class="card-footer d-flex justify-content-end">
                        <button type="submit" class="btn btn-outline-success">
                            @lang('Login')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
