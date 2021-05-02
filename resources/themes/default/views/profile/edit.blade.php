@extends('auth')

@section('title', __('Your personal account'))

@section('action_page')
    @include('profile.update-profile-information-form')
    @include('profile.update-password-form')
    @include('profile.delete-user-form')
@endsection
