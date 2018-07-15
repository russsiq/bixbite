<div class="card card-default">
	<div class="card-header">
		<i class="fa fa-th-list"></i> @if(isset($user->id)) Редактирование пользователя <b>{{$user->name}}</b> @else Новый пользователь @endif
	</div>
	<div class="card-body">
		<div class="form-group row{{ $errors->has('name') ? ' has-error' : '' }}">
			<label for="name" class="col-sm-5">@lang('name')</label>
			<div class="col-sm-7"><input type="text" name="name" value="{{ old('name', optional($user)->name) }}" class="form-control" required /></div>
		</div>

		<div class="form-group row{{ $errors->has('email') ? ' has-error' : '' }}">
			<label for="email" class="col-sm-5">@lang('email')</label>
			<div class="col-sm-7"><input type="email" name="email" value="{{ old('email', optional($user)->email) }}" class="form-control" required /></div>
		</div>

		{{-- Только собственник может менять группу пользователю. --}}
		@if ('owner' == user('role') and optional($user)->id != user('id'))
			<div class="form-group row{{ $errors->has('role') ? ' has-error' : '' }}">
				<label for="role" class="col-sm-5">@lang('role')</label>
				<div class="col-sm-7">
					<select id="role" name="role" class="form-control">
						@foreach (array_reverse($roles) as $role)
							<option value="{{ $role }}" {{ $role == old('role', optional($user)->role) ? 'selected' : ''}}>@lang($role)</option>
						@endforeach
					</select>
				</div>
			</div>
		@endif

		<div class="form-group row{{ $errors->has('password') ? ' has-error' : '' }}">
			<label for="password" class="col-sm-5">@lang('password')</label>
			<div class="col-sm-7"><input type="password" name="password" value="" class="form-control" /></div>
		</div>
		<div class="form-group row">
			<label for="password_confirmation" class="col-sm-5">@lang('password_confirmation')</label>
			<div class="col-sm-7"><input type="password" name="password_confirmation" value="" class="form-control" /></div>
		</div>

		@if (count($x_fields))
			@foreach ($x_fields as $x_field)
				{{-- DON'T use "@each(...)", because "$loop->..." and "$user->..." does not work --}}
				@include('users.partials.x_fields', ['x_field' => $x_field, 'item' => $user ?? []])
			@endforeach
		@endif

		<div class="form-group row{{ $errors->has('avatar') ? ' has-error' : '' }}">
			<label for="avatar" class="col-sm-5">@lang('avatar')</label>
			<div class="col-sm-7">
				<input type="file" name="avatar" class="form-control " />
				@if (optional($user)->getOriginal('avatar'))
					<br>
					<img src="{{ $user->avatar }}" alt="avatar_{{ $user->id }}" />
					<label class="form-text text-muted"><input type="checkbox" name="delete_avatar" value="1" /> @lang('delete_avatar')</label>
				@endif
			</div>
		</div>
	</div>

	<!-- SUBMIT Form -->
	<div class="card-footer">
		<div class="row">
			<div class="col-sm-7 offset-sm-5">
				<button type="submit" title="Ctrl+S" class="btn btn-outline-success btn-bg-white">
					<span class="d-md-none"><i class="fa fa-floppy-o"></i></span>
					<span class="d-none d-md-inline">@lang(isset($user->id) ? 'btn.save' : 'btn.add')</span>
				</button>
				<a href="{{ route('admin.users.index') }}" class="btn btn-outline-dark btn-bg-white">@lang('btn.cancel')</a>
			</div>
		</div>
	</div>
</div>
