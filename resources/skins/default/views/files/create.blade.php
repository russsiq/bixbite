@extends('layouts.app')

@section('breadcrumb')
    @component('components.breadcrumbs')
        @slot('crumbs', [
            ['action' => 'files.index', 'title' => 'filemanager'], ['action' => 'files.create', 'title' => 'creating'],
        ])
    @endcomponent
@endsection

@section('mainblock')
    <!-- Main content form -->
    <form id="form_action" name="form" action="{{ route('admin.files.store') }}" method="post" enctype="multipart/form-data" class="form-horizontal">

        @csrf
        @include('files.partials.form')
    </form>
@endsection
