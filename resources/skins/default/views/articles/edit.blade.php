@extends('layouts.app')

@section('breadcrumb')
    @component('components.breadcrumbs')
        @slot('crumbs', [
            ['action' => 'articles.index', 'title' => 'articles'], ['action' => 'articles.edit', 'title' => $article->title],
        ])
    @endcomponent
@endsection

@section('mainblock')
    <!-- Main content form -->
    <form id="form_action" name="form" action="{{ route('admin.articles.update', $article) }}" method="post" enctype="multipart/form-data" class="form-horizontal">
        <input type="hidden" name="_method" value="PUT" />
        @csrf
        @include('articles.partials.form')
    </form>
@endsection

@push('scripts')
    @include('articles.partials.form_script')
@endpush
