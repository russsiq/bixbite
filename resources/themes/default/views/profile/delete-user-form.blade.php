<section class="action_page">
    <div class="action_page__inner">
        <header class="action_page__header">
            <h2 class="mb-3 action_page__title">@lang('Delete Account')</h2>
            <p class="mb-0 text-muted">@lang('Permanently delete your account.')</p>
        </header>

        <form action="{{ route('user-password.update') }}" method="post" class="action_page__content">
            @csrf
            @method('DELETE')

    		<div class="mb-3 row">
                <p class="mb-0 text-dark fw-bold">
                    @lang('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.')
                </p>
            </div>

            <div class="mb-3 row">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.')"
                    >@lang('Delete Account')</button>
                </div>
            </div>
        </form>
    </div>
</section>
