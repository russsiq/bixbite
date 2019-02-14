@extends('layouts.app')
@section('page-layout', 'layout-right')

@section('sidebar_header')
    @include('components.sidebar_header')
@endsection

@section('header')
    @include('components.header')
@endsection

@section('mainblock')
    {{ $mainblock }}
@endsection

@section('sidebar_right')
    @include('components.sidebar_right')
@endsection

@section('sidebar_footer')
    @include('components.sidebar_footer')
@endsection

@section('footer')
    @include('components.footer')
@endsection
