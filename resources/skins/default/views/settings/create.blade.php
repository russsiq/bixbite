@extends('layouts.app')

@section('breadcrumb')
    @component('components.breadcrumbs')
        @slot('crumbs', [
            ['action' => 'settings.index', 'title' => 'settings'], ['action' => 'settings.create', 'title' => 'creating'],
        ])
    @endcomponent
@endsection

@section('mainblock')
    <!-- Main content form -->
    <form id="form_action" name="form" action="{{ route('admin.settings.store') }}" method="post" class="form-horizontal">

        @csrf
        @include('settings.partials.form_setting')
    </form>
@endsection
