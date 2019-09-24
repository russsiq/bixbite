<section class="action_page">
    <div class="action_page__inner">
        <header class="action_page__header">
            <h2 class="action_page__title">@lang('users.edit_page')</h2>
        </header>

        <form action="{{ route('profile.update', $user) }}" method="post" enctype="multipart/form-data" class="action_page__content">
            <input type="hidden" name="_method" value="PUT" />

            <div class="form-group row{{ $errors->has('name') ? ' has-error' : '' }}">
    			<label for="name" class="col-sm-4 col-form-label">@lang('auth.name')</label>
    			<div class="col-sm-6">
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" required autofocus />
                    @if ($errors->has('name'))
                        <span class="invalid-feedback">{{ $errors->first('name') }}</span>
                    @endif
                </div>
    		</div>

            <div class="form-group row{{ $errors->has('email') ? ' has-error' : '' }}">
    			<label for="email" class="col-sm-4 col-form-label">@lang('auth.email')</label>
    			<div class="col-sm-6">
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" required />
                    @if ($errors->has('email'))
                        <span class="invalid-feedback">{{ $errors->first('email') }}</span>
                    @endif
                </div>
    		</div>

            <div class="form-group row{{ $errors->has('where_from') ? ' has-error' : '' }}">
    			<label for="where_from" class="col-sm-4 col-form-label">@lang('users.where_from')</label>
    			<div class="col-sm-6">
                    <input type="text" name="where_from" value="{{ old('where_from', $user->where_from) }}" class="form-control{{ $errors->has('where_from') ? ' is-invalid' : '' }}" />
                    @if ($errors->has('where_from'))
                        <span class="invalid-feedback">{{ $errors->first('where_from') }}</span>
                    @endif
                </div>
    		</div>

            <div class="form-group row{{ $errors->has('info') ? ' has-error' : '' }}">
    			<label for="info" class="col-sm-4 col-form-label">@lang('users.info')</label>
    			<div class="col-sm-6">
                    <textarea name="info" rows="4" class="form-control{{ $errors->has('info') ? ' is-invalid' : '' }}">{{ old('info', $user->info) }}</textarea>
                    @if ($errors->has('info'))
                        <span class="invalid-feedback">{{ $errors->first('info') }}</span>
                    @endif
                </div>
    		</div>

    		<div class="form-group row{{ $errors->has('avatar') ? ' has-error' : '' }}">
    			<label for="avatar" class="col-sm-4">@lang('users.avatar')</label>
    			<div class="col-sm-6">
    				@if (optional($user)->getOriginal('avatar'))
    					<br><img src="{{ $user->avatar }}" alt="avatar_{{ $user->id }}" />
    					<label class="form-text text-muted"><input type="checkbox" name="delete_avatar" value="1" /> @lang('users.delete_avatar')</label>
    				@endif
    				<input type="file" name="avatar" class="form-control{{ $errors->has('avatar') ? ' is-invalid' : '' }}" />
    			</div>
    		</div>

            <hr>

            @if (count($x_fields))
                @foreach ($x_fields as $x_field)
                    {{-- DON'T use "@each(...)", because "$loop->..." and "$user->..." does not work --}}
                    @include('users.partials.x_fields', ['x_field' => $x_field, 'item' => $user ?? []])
                @endforeach
                <hr>
            @endif

    		<div class="form-group row{{ $errors->has('password') ? ' has-error' : '' }}">
    			<label for="password" class="col-sm-4 col-form-label">@lang('auth.password')</label>
    			<div class="col-sm-6">
                    <input type="password" name="password" value="" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" autocomplete="new-password" />
                    @if ($errors->has('password'))
                        <span class="invalid-feedback">{{ $errors->first('password') }}</span>
                    @endif
                </div>
    		</div>
    		<div class="form-group row{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
    			<label for="password_confirmation" class="col-sm-4 col-form-label">@lang('auth.password_confirmation')</label>
    			<div class="col-sm-6">
                    <input type="password" name="password_confirmation" value="" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" autocomplete="off" />
                    @if ($errors->has('password_confirmation'))
                        <span class="invalid-feedback">{{ $errors->first('password_confirmation') }}</span>
                    @endif
                </div>
    		</div>

            <div class="form-group row">
                <div class="col-md-6 offset-md-4 d-flex">
                    <button type="submit" name="_token" value="{{ pageinfo('csrf_token') }}" class="btn btn-primary">@lang('common.btn.save')</button>
                    <a href="{{ $user->profile }}" class="btn btn-outline-dark ml-auto">@lang('common.btn.cancel')</a>
                </div>
            </div>
        </form>
    </div>
</section>
