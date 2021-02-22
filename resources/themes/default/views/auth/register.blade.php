@extends('layouts.app')

@section('title', __('Register'))

@section('header')
<h1 class="h3 text-center">@lang('Register')</h1>
@endsection

@section('mainblock')
<section class="container mb-5">
    <div class="row justify-content-center">
        <div class="col col-md-5 col-xxxl-3">

            <div class="card shadow">
                <form action="{{ route('register') }}" method="post">
                    @csrf

                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">@lang('Name')</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-control"
                                autocomplete="name" autofocus required>
                            @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="email" class="form-label">@lang('Email')</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control"
                                required>
                            @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password" class="form-label">@lang('Password')</label>
                            <input id="password" type="password" name="password" value=""
                                class="form-control" autocomplete="new-password" required>
                            @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password_confirmation" class="form-label">@lang('Confirm Password')</label>
                            <input id="password_confirmation" type="password" name="password_confirmation"
                                value="{{ old('password_confirmation') }}" class="form-control"
                                autocomplete="new-password" required>
                            @error('password_confirmation')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input id="terms" type="checkbox" name="terms" value="yes" class="form-check-input">
                                <label for="terms" class="form-check-label">
                                    @lang('I agree to the :terms_of_service and :privacy_policy', [
                                    'terms_of_service' => '<a href="#">'.__('Terms of Service').'</a>',
                                    'privacy_policy' => '<a href="#">'.__('Privacy Policy').'</a>',
                                    ])
                                </label>
                                @error('terms')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('login') }}" class="btn btn-link">
                            @lang('Already registered?')
                        </a>
                        <button type="submit" class="btn btn-outline-success ml-auto">
                            @lang('Register')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
