<section class="action_page">
    <div class="action_page__inner">
        <header class="action_page__header">
            <h2 class="mb-3 action_page__title">@lang('Update Password')</h2>
            <p class="mb-0 text-muted">@lang('Ensure your account is using a long, random password to stay secure.')</p>
        </header>

        <form action="{{ route('user-password.update') }}" method="post" enctype="multipart/form-data" class="action_page__content">
            @csrf
            @method('PUT')

    		<div class="mb-3 row">
                <label for="current_password" class="col-sm-4 form-label">@lang('Current Password')</label>
    			<div class="col-sm-6">
                    <input id="current_password" type="password" name="current_password" value=""
                        class="form-control" autocomplete="current-password" required>
                    @error('current_password', 'updatePassword')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3 row">
                <label for="password" class="col-sm-4 form-label">@lang('New Password')</label>
    			<div class="col-sm-6">
                    <input id="password" type="password" name="password" value="" class="form-control"
                        autocomplete="new-password" required>
                    @error('password', 'updatePassword')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3 row">
                <label for="password_confirmation" class="col-sm-4 form-label">@lang('Confirm Password')</label>
    			<div class="col-sm-6">
                    <input id="password_confirmation" type="password" name="password_confirmation" value=""
                        class="form-control" autocomplete="new-password" required>
                    @error('password_confirmation', 'updatePassword')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3 row">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">@lang('Update password')</button>
                    <a href="{{ route('profile.show') }}" class="btn btn-outline-dark pull-right">@lang('Cancel')</a>
                </div>
            </div>
        </form>
    </div>
</section>
