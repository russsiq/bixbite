<section class="archive_page__second">
    <form action="{{ route('articles.search') }}" method="post">
        <div class="input-group mb-0">
            <input type="search" name="query" value="{{ $query }}" class="form-control"
                placeholder="@lang('common.query')" aria-label="Search" autocomplete="off" autofocus />
            <div class="input-group-append">
                <button type="submit" name="_token" value="{{ pageinfo('csrf_token') }}" class="btn btn-outline-dark">@lang('common.btn.search')</button>
            </div>
        </div>
    </form>
</section>
