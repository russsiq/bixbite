@extends('layouts.app')

@section('breadcrumb')
    @component('components.breadcrumbs')
        @slot('crumbs', [
            ['action' => $module->name . '.index', 'title' => $module->name], ['action' => 'settings.index', 'title' => 'settings'],
        ])
    @endcomponent
@endsection

@section('mainblock')
    <!-- Main content form -->
    <form id="form_action" name="form" action="{{ route('admin.settings.module_save', $module) }}" method="post" class="form-horizontal">

        @csrf
        @empty ($fields->count())
            <h4 class="alert alert-info text-center">@lang('msg.not_settings')</h4>
        @else
            @include('settings.partials.form_module')
            {{-- @include('settings.partials.form_settings') --}}
        @endempty
    </form>
@endsection
