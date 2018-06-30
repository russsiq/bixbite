<!-- Navigation bar -->
{{-- @lang('Home :page', ['page' => 2]) --}}
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('home')</a></li>
        @foreach (array_filter($crumbs) as $crumb)
            @if ($loop->last)
                <li class="breadcrumb-item active" aria-current="page">@lang($crumb['title'])</li>
            @else
                @if (\Route::has('admin.' . $crumb['action']) and ! empty($crumb['parameters']))
                    <li class="breadcrumb-item"><a href="{{ route('admin.' . $crumb['action'], $crumb['parameters']) }}">@lang($crumb['title'])</a></li>
                @elseif (\Route::has('admin.' . $crumb['action']))
                    <li class="breadcrumb-item"><a href="{{ route('admin.' . $crumb['action']) }}">@lang($crumb['title'])</a></li>
                @else
                    <li class="breadcrumb-item">@lang($crumb['title'])</li>
                @endif
            @endif
        @endforeach
    </ol>
</nav>
