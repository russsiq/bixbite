@extends('errors::layout')

@section('title', __('common.error.403.title'))

@section('message', __($exception->getMessage() ?: 'common.error.403.message'))
