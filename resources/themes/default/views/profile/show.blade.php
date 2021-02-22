@extends('layouts.app')

@section('title', __('Profile'))

@section('header')
<h1 class="h2">@lang('Profile')</h1>
{{-- <p class="mt-3 text-muted"></p> --}}
@endsection

@section('mainblock')

@include('profile.update-profile-information-form')

@include('profile.update-password-form')

@endsection
