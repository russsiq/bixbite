@extends('layouts.app')

@section('title', __('Home'))

@section('header')
<h1>Welcome back</h1>
@endsection

@section('mainblock')
<section class="container">
    <div class="row">
        @foreach (range(1, 3) as $item)
        <div class="col">
            <div class="card">
                <div class="card-header">
                    Featured # {{ $item }}
                </div>
                <div class="card-body">
                    <h5 class="card-title">Special title treatment</h5>
                    <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                    <a href="#" class="btn btn-primary">Go somewhere</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endsection
