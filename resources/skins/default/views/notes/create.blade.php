@extends('layouts.app')

@section('breadcrumb')
    @component('components.breadcrumbs')
        @slot('crumbs', [
            ['action' => 'notes.index', 'title' => 'notes'], ['action' => 'notes.create', 'title' => 'creating'],
        ])
    @endcomponent
@endsection

@section('mainblock')
    <!-- Main content form -->
    <form id="form_action" name="form" action="{{ route('admin.notes.store') }}" method="post" class="form-horizontal">

        @csrf
        @include('notes.partials.form')
    </form>
@endsection
