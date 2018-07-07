<div class="profile_page__wall">
    <div class="wall__inner">
        @if (user('id') == $user->id)
        <form action="{{ $user->comment_store_action }}" method="post" enctype="multipart/form-data" class="wall__form">
            @csrf

            <textarea name="content" class="wall_form__content" rows="2" placeholder="What's in your mind today?" required>{{ old('content') }}</textarea>
            <div class="wall_form__footer">
                <button type="submit" name="parent_id" value class="wall_form__submit" title="@lang('users.btn.profile.post')">
                    <i class="fa fa-paper-plane-o"></i>
                </button>

                {{-- <ul class="nav wall_form__pills">
                    <li class="wall_form_pills__item"><a href="#"><i class="fa fa-map-marker"></i></a></li>
                    <li class="wall_form_pills__item"><a href="#"><i class="fa fa-camera"></i></a></li>
                    <li class="wall_form_pills__item"><a href="#"><i class=" fa fa-film"></i></a></li>
                    <li class="wall_form_pills__item"><a href="#"><i class="fa fa-microphone"></i></a></li>
                </ul> --}}
            </div>
        </form>
        @endif

        @foreach ($user->posts as $key => $post)
            <article id="comment-{{ $post->id }}" class="wall__post">
                <div class="wall_post__inner">
                    <figure class="wall_post__avatar">
                        <img src="{{ $user->avatar }}" alt="{{ $user->name }}" width="38px" />
                    </figure>
                    <header class="wall_post__header">
                        <b class="wall_post__username"><a href="{{ $user->profile }}">{{ $user->name }}</a></b>
                        <br>
                        <small class="wall_post__datetime">{{ $post->created }}</small>
                    </header>

                    <div class="wall_post__content">
                        @if (strlen($post->content) > 250)
                            {!! $post->content !!} <br>
                        @else
                        <h5>{{ $post->content }}</h5>
                        @endif

                        {{-- <button type="button" class="btn btn-link btn-sm"><i class="fa fa-share"></i> Share</button>
                        <button type="button" class="btn btn-link btn-sm"><i class="fa fa-thumbs-o-up"></i> Like</button> --}}
                        @if ($post->children and $count = $post->children->count())
                            <span class="pull-right text-muted">{{ $count.' '.trans_choice('comments.num', $count) }} </span>
                        @endif
                    </div>

                    @if ($post->children)
                        <div class="box-footer box-comments" style="display: block; width: 100%;">
                            @foreach ($post->children->sortBy('id') as $child)
                                <div id="comment-{{ $child->id }}" class="input-group p-2">
                                    <div class="input-group-prepend" style="height:31px">
                                        <img class="img-responsive img-circle img-sm" src="{{ $child->user->avatar }}" alt="{{ $child->user->name }}" width="31px" height="31px" />
                                    </div>
                                    <div class="form-control ml-2 border-0 p-0">
                                        <span class="username">
                                            <b><a href="{{ $child->user->profile }}">{{ $child->user->name }}</a></b>
                                            <small class="text-muted pull-right">{{ $child->created }}</small>
                                        </span>
                                        <br>
                                        {{ $child->content }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if (user())
                        <div class="box-footer" style="display: block; width: 100%;">
                            <form action="{{ $user->comment_store_action }}" method="post" enctype="multipart/form-data">
                                @csrf

                                <div class="input-group input-group-sm p-2">
                                    <div class="input-group-prepend" style="height:31px">
                                        <img class="img-responsive img-circle img-sm" src="{{ user('avatar') }}" alt="{{ user('name') }}" width="31px" height="31px" />
                                    </div>
                                    <input type="text" name="content" class="form-control ml-2" placeholder="Press enter to post comment" autocomplete="off" />
                                    <div class="input-group-append">
                                        <button type="submit" name="parent_id" value="{{ $post->id }}" class="btn btn-outline-secondary" title="@lang('users.btn.profile.post')">
                                            <i class="fa fa-paper-plane-o"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </article>
        @endforeach
    </div>
</div>
