@extends('layouts.app')

@section('breadcrumb')
    @component('components.breadcrumbs')
        @slot('crumbs', [
            ['action' => 'x_fields.index', 'title' => 'x_fields'], ['action' => 'x_fields.create', 'title' => 'creating'],
        ])
    @endcomponent
@endsection

@section('mainblock')
    <!-- Main content form -->
    <form id="form_action" name="form" action="{{ route('admin.x_fields.store') }}" method="post" class="form-horizontal">

        @csrf
        @include('x_fields.partials.form')
    </form>
@endsection
