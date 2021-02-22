@extends('layouts.app')

@section('title', __('Verify Email Address'))

@section('header')
<h1 class="h3 text-center">@lang('Verify Your Email Address')</h1>
@endsection

@section('mainblock')
<section class="container mb-5">
    <div class="row justify-content-center">
        <div class="col col-md-5 col-xxxl-3">

            @if (session('status') === 'verification-link-sent')
            <div class="alert alert-info mb-3">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
            @endif

            <div class="card shadow">
                <div class="card-body">
                    <p class="mb-3 text-muted">
                        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                    </p>

                    <form action="{{ route('verification.send') }}" method="post">
                        @csrf

                        <button type="submit" class="w-100 btn btn-outline-success">
                            @lang('Resend Verification Email')
                        </button>
                    </form>

                    <form class="mt-3 text-muted" action="{{ route('logout') }}" method="post">
                        @csrf

                        <button type="submit" class="btn btn-link">
                            @lang('Logout')
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
