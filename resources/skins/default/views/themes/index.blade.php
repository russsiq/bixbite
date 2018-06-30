@extends('layouts.app')

@section('breadcrumb')
    @component('components.breadcrumbs')
        @slot('crumbs', [
            ['action' => 'themes.index', 'title' => 'themes']
        ])
    @endcomponent
@endsection

@section('mainblock')
    <!-- List of themes: BEGIN -->
    <div id="theme-card-list" class="row">
    	@foreach ($themes as $theme)
    	<div class="col-12 col-lg-6 mb-4">
            <div class="theme-card" style="background-image: url({{ $theme->screenshot ?? '//via.placeholder.com/350x250' }})">
        		<div class="color-overlay clearfix">
        			<div class="icon-block" style="text-shadow: 0 1px 2px #000;">
                        @if ($loop->first)
                            <a class="icon-block__icon" href="{{ route('admin.templates.index') }}"><i class="fa fa-2x fa-paint-brush"></i></a>
                        @endif
    				</div>
        			<div class="theme-content">
        				<div class="theme-header">
        					<h3 class="theme-title">{{ $theme->id }}</h3>
        					<h4 class="theme-info"><a href="{{ $theme->author_url }}" target="_blank">{{ $theme->author }}</a>, v{{ $theme->version }} ({{ $theme->reldate }})</h4>
        				</div>
        				<p class="theme-desc">{{ $theme->title }}<br>{{ teaser($theme->description, 150) }}</p>
                        @if ($loop->first)
                            <a href="{{ route('admin.settings.module', ['module'=>'themes']) }}" class="btn btn-outline-primary theme-btn">@lang('btn.customize')</a>
                        @else
                            <button type="submit" name="APP_THEME" value="{{ $theme->id }}" class="btn btn-outline-primary theme-btn">@lang('btn.activate')</button>
                        @endif
        			</div>
        		</div>
    		</div>
    	</div>
        @endforeach
    </div>
@endsection
