@extends('layouts.app')

@section('breadcrumb')
    @component('components.breadcrumbs')
        @slot('crumbs', [
            ['action' => 'comments.index', 'title' => 'comments'], ['action' => 'comments.edit', 'title' => $comment->id],
        ])
    @endcomponent
@endsection

@section('mainblock')
    <!-- Main content form -->
    <form id="form_action" name="form" action="{{ route('admin.comments.update', $comment) }}" method="post" class="form-horizontal">
        <input type="hidden" name="_method" value="PUT" />
        @csrf
        @include('comments.partials.form')
    </form>
@endsection
