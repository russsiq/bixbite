@extends('layouts.app')

@section('title', __('Home'))

@section('mainblock')
<x-widget::articles-featured :parameters="[
    'title' => 'Популярные новости',
    'limit' => 3,
    'template' => 'components.widgets.articles-featured',
]" />
@endsection
