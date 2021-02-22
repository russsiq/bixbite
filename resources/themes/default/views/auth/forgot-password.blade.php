@extends('layouts.app')

@section('title', __('Send Password Reset Link'))

@section('header')
<h1 class="h3 text-center">@lang('Reset Password')</h1>
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
                <form action="{{ route('password.email') }}" method="post">
                    @csrf

                    <div class="card-body">
                        <p class="mb-3 text-muted">
                            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                        </p>

                        <div class="form-group mb-3">
                            <label for="email" class="form-label">@lang('Email')</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control"
                                autofocus required>
                            @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-end">
                        <button type="submit" class="btn btn-outline-success">
                            @lang('Send Password Reset Link')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
