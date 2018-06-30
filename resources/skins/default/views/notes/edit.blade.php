@extends('layouts.app')

@section('breadcrumb')
    @component('components.breadcrumbs')
        @slot('crumbs', [
            ['action' => 'notes.index', 'title' => 'notes'], ['action' => 'notes.edit', 'title' => $note->id . ': ' . $note->title],
        ])
    @endcomponent
@endsection

@section('mainblock')
    <!-- Main content form -->
    <form id="form_action" name="form" action="{{ route('admin.notes.update', $note) }}" method="post" class="form-horizontal">
        <input type="hidden" name="_method" value="PUT" />
        @csrf
        @include('notes.partials.form')
    </form>
@endsection
