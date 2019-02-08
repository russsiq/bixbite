@extends('layouts.app')

@section('breadcrumb')
    @component('components.breadcrumbs')
        @slot('crumbs', [
            [
                'action' => 'categories.index',
                'title' => 'categories'
            ], [
                'action' => 'categories.edit',
                'title' => '#'.$category->id.' '.$category->title
            ],
        ])
    @endcomponent
@endsection

@section('mainblock')
    <!-- Main content form -->
    <form id="form_action" name="form" action="{{ route('admin.categories.update', $category) }}" method="post" enctype="multipart/form-data" class="form-horizontal">
        <input type="hidden" name="_method" value="PUT" />
        @csrf
        @include('categories.partials.form')
    </form>
@endsection
