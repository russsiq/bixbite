@extends('layouts.app')

@section('breadcrumb')
    @component('components.breadcrumbs')
        @slot('crumbs', [
            ['action' => 'x_fields.index', 'title' => 'x_fields'], ['action' => 'x_fields.edit', 'title' => $x_field->name],
        ])
    @endcomponent
@endsection

@section('mainblock')
    <!-- Main content form -->
    <form action="{{ route('admin.x_fields.update', $x_field) }}" method="post" class="form-horizontal">
        <input type="hidden" name="_method" value="PUT" />
        @csrf
        @include('x_fields.partials.form')
    </form>
@endsection
