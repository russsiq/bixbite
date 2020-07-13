<div class="form-group row {{ $errors->has($x_field->name) ? ' has-error' : '' }}">
	<label class="col-sm-4 col-form-label">{{ $x_field->title }}</label>
	<div class="col-sm-6">
		@if ('boolean' == $x_field->type)
			<select name="{{ $x_field->name }}" class="form-control" {{ $x_field->html_flags }}>
			@foreach(['no', 'yes'] as $key => $var)
				<option value="{{ $key }}" {{ old($x_field->name, optional($item)->{$x_field->name}) == $key ? 'selected' : '' }}>@lang($var)</option>
			@endforeach
			</select>
		@elseif ('integer' == $x_field->type)
			<input type="number" name="{{ $x_field->name }}" class="form-control" {{ $x_field->html_flags }}
				value="{{ old($x_field->name, optional($item)->{$x_field->name}) }}" />
		@elseif ('text' == $x_field->type)
			<textarea name="{{ $x_field->name }}" class="form-control" {{ $x_field->html_flags }}>{{ old($x_field->name, optional($item)->{$x_field->name}) }}<textarea>
		@elseif ('array' == $x_field->type)
			<select name="{{ $x_field->name }}" class="form-control" {{ $x_field->html_flags }}>
			@foreach($x_field->params as $parameter)
				<option value="{{ $parameter['key'] }}" {{ old($x_field->name, optional($item)->{$x_field->name}) == $parameter['key'] ? 'selected' : '' }}>{{ $parameter['value'] }}</option>
			@endforeach
			</select>
		@elseif ('timestamp' == $x_field->type)
			<input type="datetime-local" name="{{ $x_field->name }}" class="form-control" {{ $x_field->html_flags }}
				value="{{ old($x_field->name, optional($item)->{$x_field->name}) }}" />
		@else
			{{-- 'string' == $x_field->type As default --}}
			<input type="text" name="{{ $x_field->name }}" class="form-control" {{ $x_field->html_flags }} maxlength="255"
				value="{{ old($x_field->name, optional($item)->{$x_field->name}) }}" />
		@endif
	</div>
</div>
