@extends('layouts.app')

@section('breadcrumb')
    @component('components.breadcrumbs')
        @slot('crumbs', [
            ['action' => 'files.index', 'title' => 'filemanager'], ['action' => 'files.edit', 'title' => $file->id . ': ' . $file->title],
        ])
    @endcomponent
@endsection

@section('mainblock')
    <!-- Main content form -->
    <form id="form_action" name="form" action="{{ route('admin.files.update', $file) }}" method="post" enctype="multipart/form-data" class="form-horizontal">
        <input type="hidden" name="_method" value="PUT" />
        @csrf
        @include('files.partials.form')
    </form>
@endsection
