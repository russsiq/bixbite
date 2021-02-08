@extends('layouts.app')

@section('title', __('Verify Email Address'))

@section('header')
<h1>@lang('Verify Your Email Address')</h1>
<p class="mt-3 text-muted">
    {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
</p>
@endsection

@section('mainblock')
<section class="container mb-5">
    <div class="row">
        <div class="col">
            @if (session('status') === 'verification-link-sent')
                <div class="alert alert-info  mb-3">
                    {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                </div>
                @endif

                <div class="d-flex justify-content-between">
                    <form action="{{ route('verification.send') }}" method="post">
                        @csrf

                        <button type="submit" class="btn btn-outline-success">
                            @lang('Resend Verification Email')
                        </button>
                    </form>

                    <form action="{{ route('logout') }}" method="post">
                        @csrf

                        <button type="submit" class="btn btn-outline-success">
                            @lang('Logout')
                        </button>
                    </form>
                </div>
        </div>
    </div>
</section>
@endsection
