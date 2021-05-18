<section class="action_page">
    <div class="action_page__inner">
        <header class="action_page__header">
            <h2 class="mb-3 action_page__title">@lang('Profile Information')</h2>
            <p class="mb-0 text-muted">@lang("Update your account's profile information and email address.")</p>
        </header>

        <form action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data" class="action_page__content">
            @csrf
            @method('PUT')

            <div class="mb-3 row">
    			<label for="name" class="col-sm-4 col-form-label">@lang('Name')</label>
    			<div class="col-sm-6">
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required />
                    @error('name', 'updateProfileInformation')
                        <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
                    @enderror
                </div>
    		</div>

            <div class="mb-3 row">
    			<label for="email" class="col-sm-4 col-form-label">@lang('Email')</label>
    			<div class="col-sm-6">
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required />
                    @error('email', 'updateProfileInformation')
                        <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
                    @enderror
                </div>
    		</div>

            {{-- <div class="mb-3 row">
    			<label for="location" class="col-sm-4 col-form-label">@lang('Location')</label>
    			<div class="col-sm-6">
                    <input type="text" name="location" value="{{ old('location', $user->location) }}" class="form-control" />
                    @error('location', 'updateProfileInformation')
                        <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
                    @enderror
                </div>
    		</div>

            <div class="mb-3 row">
    			<label for="info" class="col-sm-4 col-form-label">@lang('Info')</label>
    			<div class="col-sm-6">
                    <textarea id="info" name="info" rows="4" class="form-control">{{ old('info', $user->info) }}</textarea>
                    @error('info', 'updateProfileInformation')
                        <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
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
                    @error('avatar', 'updateProfileInformation')
                        <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
                    @enderror
    			</div>
    		</div>

            <hr> --}}

            @if (count($x_fields))
                @foreach ($x_fields as $x_field)
                    {{-- DON'T use "@each(...)", because "$loop->..." and "$user->..." does not work --}}
                    @include('profile.x_fields', ['x_field' => $x_field, 'item' => $user ?? []])
                @endforeach
                <hr>
            @endif

            <div class="mb-3 row">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">@lang('Update profile')</button>
                    <a href="{{ $user->profile }}" class="btn btn-outline-dark pull-right">@lang('Cancel')</a>
                </div>
            </div>
        </form>
    </div>
</section>
