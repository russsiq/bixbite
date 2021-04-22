<section class="action_page">
    <div class="action_page__inner">
        <header class="action_page__header">
            <h2 class="action_page__title">@lang('users.edit_page')</h2>
        </header>

        <form action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data" class="action_page__content">
            @csrf
            @method('PUT')

            <div class="mb-3 row">
    			<label for="name" class="col-sm-4 col-form-label">@lang('Name')</label>
    			<div class="col-sm-6">
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required />
                    @error('name')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>
    		</div>

            <div class="mb-3 row">
    			<label for="email" class="col-sm-4 col-form-label">@lang('Email')</label>
    			<div class="col-sm-6">
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required />
                    @error('email')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>
    		</div>

            <div class="mb-3 row">
    			<label for="where_from" class="col-sm-4 col-form-label">@lang('Where from')</label>
    			<div class="col-sm-6">
                    <input type="text" name="where_from" value="{{ old('where_from', $user->where_from) }}" class="form-control" />
                    @error('where_from')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>
    		</div>

            <div class="mb-3 row">
    			<label for="info" class="col-sm-4 col-form-label">@lang('Info')</label>
    			<div class="col-sm-6">
                    <textarea id="info" name="info" rows="4" class="form-control">{{ old('info', $user->info) }}</textarea>
                    @error('info')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>
    		</div>

    		<div class="mb-3 row">
    			<label for="avatar" class="col-sm-4">@lang('Avatar')</label>
    			<div class="col-sm-6">
    				@if (optional($user)->getRawOriginal('avatar'))
    					<br><img src="{{ $user->avatar }}" alt="avatar_{{ $user->id }}" />
    					<label class="form-text text-muted"><input type="checkbox" name="delete_avatar" value="1" /> @lang('users.delete_avatar')</label>
    				@endif
    				<input type="file" name="avatar" class="form-control" />
                    @error('avatar')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
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

    		<div class="mb-3 row">
    			<label for="password" class="col-sm-4 col-form-label">@lang('Password')</label>
    			<div class="col-sm-6">
                    <input type="password" name="password" value="" class="form-control" autocomplete="new-password" />
                    @error('password')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>
    		</div>

    		<div class="mb-3 row{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
    			<label for="password_confirmation" class="col-sm-4 col-form-label">@lang('Confirm Password')</label>
    			<div class="col-sm-6">
                    <input type="password" name="password_confirmation" value="" class="form-control" autocomplete="new-password" />
                    @error('password_confirmation')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>
    		</div>

            <div class="mb-3 row">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">@lang('Save')</button>
                    <a href="{{ $user->profile }}" class="btn btn-outline-dark pull-right">@lang('Cancel')</a>
                </div>
            </div>
        </form>
    </div>
</section>
