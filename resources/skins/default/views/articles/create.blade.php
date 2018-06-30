@extends('layouts.app')

@section('breadcrumb')
    @component('components.breadcrumbs')
        @slot('crumbs', [
            ['action' => 'articles.index', 'title' => 'articles'], ['action' => 'articles.create', 'title' => 'creating'],
        ])
    @endcomponent
@endsection

@section('mainblock')
    <!-- Main content form -->
    <form id="form_action" name="form" action="{{ route('admin.articles.store') }}" method="post" enctype="multipart/form-data" class="form-horizontal">

        @csrf
        @include('articles.partials.form')
    </form>
@endsection

@push('scripts')
    @include('articles.partials.form_script')
@endpush
