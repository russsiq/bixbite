@extends('auth')

@section('title', __('users.edit_page'))

@section('action_page')
    @include('profile.update-profile-information-form')
    @include('profile.update-password-form')
@endsection
