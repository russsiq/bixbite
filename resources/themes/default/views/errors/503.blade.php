@extends('errors::layout')

@section('title', __('common.error.503.title'))

@section('message', __($exception->getMessage() ?: 'common.error.503.message'))
