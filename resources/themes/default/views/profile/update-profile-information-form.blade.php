<div class="container mb-5">
    <div class="row">
        <div class="col-md-4">
            <h5>@lang('Profile Information')</h5>
            <p class="text-mutted">@lang("Update your account's profile information and email address.")</p>
        </div>
        <div class="col-md-8">
            <div class="card shadow">
                <form action="{{ route('user-profile-information.update') }}" method="post">
                    @csrf
                    @method('put')

                    <div class="card-body">
                        @if ('profile-information-updated' === session('status'))
                        <div class="alert alert-info  mb-3">@lang('Saved.')</div>
                        @endif

                        <div class="form-group mb-3">
                            <label for="name" class="form-label">@lang('Name')</label>
                            <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control"
                                autocomplete="name" autofocus required>
                            @error('name', 'updateProfileInformation')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="email" class="form-label">@lang('Email')</label>
                            <input id="email" type="" name="email" value="{{ old('email', $user->email) }}" class="form-control"
                                required>
                            @error('email', 'updateProfileInformation')
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


