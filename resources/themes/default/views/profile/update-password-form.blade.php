<div class="container mb-5">
    <div class="row">
        <div class="col-md-4">
            <h5>@lang('Update Password')</h5>
            <p class="text-mutted">@lang('Ensure your account is using a long, random password to stay secure.')</p>
        </div>
        <div class="col-md-8">
            <div class="card shadow">
                <form action="{{ route('user-password.update') }}" method="post">
                    @csrf
                    @method('put')

                    <div class="card-body">
                        @if ('password-updated' === session('status'))
                        <div class="alert alert-info  mb-3">@lang('Saved.')</div>
                        @endif

                        <div class="form-group mb-3">
                            <label for="current_password" class="form-label">@lang('Current Password')</label>
                            <input id="current_password" type="password" name="current_password" value=""
                                class="form-control" autocomplete="current-password" required>
                            @error('current_password', 'updatePassword')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password" class="form-label">@lang('New Password')</label>
                            <input id="password" type="password" name="password" value="" class="form-control"
                                autocomplete="new-password" required>
                            @error('password', 'updatePassword')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password_confirmation" class="form-label">@lang('Confirm Password')</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" value=""
                                class="form-control" autocomplete="new-password" required>
                            @error('password_confirmation', 'updatePassword')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-end">
                        <button type="submit" class="btn btn-outline-success">
                            @lang('Save')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
