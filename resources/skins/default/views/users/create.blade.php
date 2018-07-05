@extends('layouts.app')

@section('breadcrumb')
    @component('components.breadcrumbs')
        @slot('crumbs', [
            ['action' => 'users.index', 'title' => 'users'], ['action' => 'users.create', 'title' => 'adding'],
        ])
    @endcomponent
@endsection

@section('mainblock')
    <!-- Info content -->
    <div class="page_main">
        <div class="row">
            <!-- MAIN CONTENT -->
        	<div class="col col-sm-6">
                <form id="form_action" name="form" action="{{ route('admin.users.store') }}" method="post" enctype="multipart/form-data" class="form-horizontal">

                    @csrf
                    @include('users.partials.form')
                </form>
            </div>
            <div class="col col-sm-6">
                <div class="alert alert-info">
                    <h4 class="alert-heading">Обратите внимание</h4>
                    <p>На электронную почту, адрес которой будет указан в этой форме, будет выслано письмо для подтверждения регистрации.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
