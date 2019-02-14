@extends('layouts.app')

@section('sidebar_header')
    @include('components.sidebar_header')
@endsection

@section('header')
    @include('components.header')
@endsection

@section('mainblock')
    {{ $mainblock }}
@endsection

@section('sidebar_footer')
    @include('components.sidebar_footer')
@endsection

@section('footer')
    @include('components.footer')
@endsection
