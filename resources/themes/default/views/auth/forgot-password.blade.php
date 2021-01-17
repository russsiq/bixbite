@extends('layouts.app')

@section('mainblock')
<section class="action_page bg-white">
    <div class="action_page__inner">
        <header class="action_page__header">
            <h2 class="action_page__title">@lang('auth.reset')</h2>
        </header>

        <section class="action_page__content">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST">
                <div class="mb-3 row">
                    <label for="email" class="col-md-4 col-form-label">@lang('auth.email')</label>
                    <div class="col-md-6">
                        <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" autocomplete="email" required autofocus />
                        @error('email')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" name="_token" value="{{ pageinfo('csrf_token') }}" class="btn btn-primary">@lang('auth.btn.send')</button>
                    </div>
                </div>
            </form>
        </section>
    </div>
</section>
@endsection
