@extends('layouts.app')

@section('breadcrumb')
    @component('components.breadcrumbs')
        @slot('crumbs', [
            ['action' => 'users.index', 'title' => 'users'], ['action' => 'users.edit', 'title' => $user->name .  ' ['. $user->id.']'],
        ])
    @endcomponent
@endsection

@section('mainblock')
    <div class="row">
    	<div class="col col-sm-6">
            <!-- Main content form -->
            <form id="form_action" name="form" action="{{ route('admin.users.update', $user) }}" method="post" enctype="multipart/form-data" class="form-horizontal">
                <input type="hidden" name="_method" value="PUT" />
                @csrf
                @include('users.partials.form')
            </form>
        </div>
        <div class="col col-sm-6">

        </div>
    </div>
@endsection
