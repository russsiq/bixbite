{{-- Атрибуты `id` используются JavaScript. --}}
<section id="comments" class="comments">
    <div class="comments__inner">
        <div class="comments__header">
            <h4 class="comments__title">@lang('comments.title')</h4>
        </div>

        {{-- Присутствие данного контейнера обязательно. Он используется для комментариев, добавляемых по AJAX. --}}
        <ol id="comments_list" class="comments__list">
            @each('comments.show', $entity->comments, 'comment')
        </ol>

        @include('comments.partials.form')
    </div>
</section>
