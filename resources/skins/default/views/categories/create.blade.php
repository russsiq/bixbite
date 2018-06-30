@extends('layouts.app')

@section('breadcrumb')
    @component('components.breadcrumbs')
        @slot('crumbs', [
            ['action' => 'categories.index', 'title' => 'categories'], ['action' => 'categories.create', 'title' => 'creating'],
        ])
    @endcomponent
@endsection

@section('mainblock')
    <!-- Main content form -->
    <form id="form_action" name="form" action="{{ route('admin.categories.store') }}" method="post" enctype="multipart/form-data" class="form-horizontal">

        @csrf
        @include('categories.partials.form')
    </form>
@endsection
