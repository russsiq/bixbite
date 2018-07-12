@extends('layouts.app')

@section('mainblock')
    <div class="row mb-5">
        <div class="col-md-6">
            <div class="theme-card" style="background-image: url({{ $theme->screenshot ?? '//via.placeholder.com/350x250' }})">
                <div class="color-overlay clearfix">
                    @can ('admin.themes')
                        <div class="icon-block" style="text-shadow: 0 1px 2px #000;">
                            <a class="icon-block__icon" href="{{ route('admin.templates.index') }}"><i class="fa fa-2x fa-paint-brush"></i></a>
        				</div>
                    @endcan
                    <div class="theme-content">
                        <div class="theme-header">
                            <h3 class="theme-title">{{ $theme->id }}</h3>
                            <h4 class="theme-info"><a href="{{ $theme->author_url }}" target="_blank">{{ $theme->author }}</a>, v{{ $theme->version }} ({{ $theme->reldate }})</h4>
                        </div>
                        <p class="theme-desc">{{ $theme->title }}. {{ teaser($theme->description, 150) }}</p>
                        @can ('admin.themes')
                            <a href="{{ route('admin.settings.module', ['module'=>'themes']) }}" class="btn btn-outline-primary theme-btn">@lang('btn.customize')</a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            @widget('notes.latest', [
                'active' => 1,
                'widget_title' => __('notes'),
            ])
        </div>
    </div>

    <!-- List of modules: BEGIN -->
    <div class="row">
        @foreach ($modules as $key => $module)
            <div class="col-md-4">
    			<div class="card card-module">
    				{{-- <div class="card-header"><i class="{{ $module->icon }} fa-2x rounded-circle"></i> @lang($module->name)</div> --}}
                    <img class="card-img-background" src="{{ skin_asset('images/background_module.jpg') }}" alt="{{ $module->title ?? $module->name }}">
                    <a href="{{
                        \Route::has('admin.'.$module->name.'.index')
                        ? route('admin.'.$module->name.'.index')
                        : route('admin.settings.module', $module)
                        }}" class="card-module-icon"><i class="{{ $module->icon }}"></i></a>
    				<div class="card-body">
                        <h4 class="card-title">@lang($module->title ?? $module->name)</h4>
                        <div class="icon-block">
                            <a href="{{ route('admin.settings.module', $module) }}" title="@lang('settings')" class="btn"><i class="fa fa-cogs"></i></a>

                            @if ('users' == $module->name)
                                @can ('admin.privileges.index')
                                    <a href="{{ route('admin.privileges.index') }}" title="@lang('privileges')" class="btn"><i class="fa fa-user-secret"></i></a>
                                @endcan
                            @endif

                            @if ('users' == $module->name or 'articles' == $module->name)
                                <a href="{{-- route('admin.xfields.module', $module) --}}" title="@lang('xfields')" class="btn"><i class="fa fa-columns"></i></a>
                            @endif

                            {{-- @if ('system' == $module->name)
                                <a href="{{ route('admin.database.index') }}" title="@lang('database')" class="btn"><i class="fa fa-database"></i></a>
                                <a href="{{ route('admin.routing.index') }}" title="@lang('routing')" class="btn"><i class="fa fa-map-signs"></i></a>
                            @endif --}}
                        </div>
                    </div>
    			</div>
    		</div>
        @endforeach
    </div>
@endsection
