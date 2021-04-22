@extends('auth')

@section('title', __('Verify Email Address'))

@section('action_page')
<section class="action_page">
    <div class="action_page__inner">
        <header class="action_page__header">
            <h2 class="action_page__title">@lang('Verify Email Address')</h2>
        </header>

        <section class="action_page__content">
            <p class="mb-3 text-muted">
                @lang('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.')
            </p>

            @if (session('status') === 'verification-link-sent')
            <div class="alert alert-info mb-3">
                @lang('A new verification link has been sent to the email address you provided during registration.')
            </div>
            @endif

            <form action="" method="POST">
                @csrf

                <div class="mb-3 row">
                    <div class="col-12">
                        <button type="submit" formaction="{{ route('verification.send') }}" class="btn btn-primary">
                            @lang('Resend Verification Email')
                        </button>

                        <button type="submit" formaction="{{ route('logout') }}" class="btn btn-link pull-right">
                            @lang('Logout')
                        </button>
                    </div>
                </div>
            </form>
        </section>
    </div>
</section>
@endsection
