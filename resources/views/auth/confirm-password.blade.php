@extends('layouts.app')

@section('title', __('Confirm Password'))

@section('header')
<h1>@lang('Confirm Password')</h1>
<p class="mt-3 text-muted">
    {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
</p>
@endsection

@section('mainblock')
<section class="container mb-5">
    <div class="row">
        <div class="col col-md-5 col-xxxl-3 ml-auto">
            <div class="card shadow">
                <form action="{{ route('password.confirm') }}" method="post">
                    @csrf

                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="password" class="form-label">@lang('Password')</label>
                            <input id="password" type="password" name="password" value="" class="form-control"
                                autofocus required>
                            @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-end">
                        <button type="submit" class="btn btn-outline-success">
                            @lang('Confirm')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
