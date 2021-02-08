@extends('layouts.app')

@section('title', __('Reset Password'))

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
                <form action="{{ route('password.update') }}" method="post">
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">@lang('Email')</label>
                            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}"
                                class="form-control" autofocus required>
                            @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password" class="form-label">@lang('Password')</label>
                            <input id="password" type="password" name="password" value="" class="form-control"
                                autocomplete="new-password" required>
                            @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password_confirmation" class="form-label">@lang('Confirm Password')</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" value=""
                                class="form-control" autocomplete="new-password" required>
                            @error('password_confirmation')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-end">
                        <button type="submit" class="btn btn-outline-success">
                            @lang('Reset Password')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
