@extends('layouts.app')

@section('breadcrumb')
    @component('components.breadcrumbs')
        @slot('crumbs', [
            ['action' => 'settings.index', 'title' => 'settings'], ['action' => 'settings.edit', 'title' => $setting->title],
        ])
    @endcomponent
@endsection

@section('mainblock')
    <!-- Main content form -->
    <form id="form_action" name="form" action="{{ route('admin.settings.update', $setting) }}" method="post" class="form-horizontal">
        <input type="hidden" name="_method" value="PUT" />
        @csrf
        @include('settings.partials.form_setting')
    </form>
@endsection
