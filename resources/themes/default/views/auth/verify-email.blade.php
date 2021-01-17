@extends('layouts.app')

@section('mainblock')
<section class="action_page bg-white">
    <div class="action_page__inner">
        <header class="action_page__header">
            <h2 class="action_page__title">@lang('auth.verify')</h2>
        </header>

        <section class="action_page__content">
            @if (session('resent'))
                <div class="alert alert-success" role="alert">
                    @lang('auth.verify_resent')
                </div>
            @endif

            <p>@lang('auth.verify_before')</p>
            <p>@lang('auth.verify_resend')</p>

            <form action="{{ route('verification.resend') }}" method="POST">
                <div class="mb-3 text-center">
                    <button type="submit" name="_token" value="{{ pageinfo('csrf_token') }}" class="btn btn-primary">@lang('auth.btn.resend')</button>
                </div>
            </form>
        </section>
    </div>
</section>
@endsection
