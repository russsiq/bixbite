@if (count($sections) > 1)
    <ul class="nav nav-tabs" role="tablist">
        @foreach ($sections as $key => $section)
            <li class="nav-item {{ $loop->first ? 'active' : '' }}">
                <a  id="{{ $section }}-tab"
                    href="#pane-{{ $section }}"
                    aria-controls="{{ $section }}"
                    class="nav-link {{ $loop->first ? 'active' : '' }}"
                    aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                    data-toggle="tab"
                    role="tab"
                    >@lang('section.' . $section)</a>
            </li>
        @endforeach
    </ul>
    <br/>
@endif

<div id="settings_tab" class="tab-content">
    @foreach ($fields as $key => $section)
        <div id="pane-{{ $key }}" aria-labelledby="contact-tab" class="tab-pane {{ $loop->first ? 'active' : '' }}" role="tabpanel">
            @foreach ($section as $key => $fieldset)
                <div class="card card-default">
                	<div class="card-header"><i class="fa fa-th-list"></i> @lang('legend.' . $key)</div>
                	<div class="card-body">
                		@foreach ($fieldset as $field)
                        <div class="form-group row {{ $errors->has($field->name) ? ' has-error' : '' }}" style="position:relative">
                            @can ('admin.settings.modify')
                                <sup class="pull-right" style="position:absolute;top:13px;right:28px;z-index:2;">
                                    <a href="{{ route('admin.settings.edit', $field->id) }}"><i class="fa fa-pencil"></i></a>
                                </sup>
                            @endcan
                            @if ('html' == $field->type)
                                {{ $field->input }}
                            @else
                               <label class="col-sm-7 control-label">
                                    {{ $field->title }}
                                    @isset ($field->descr)<small class="form-text text-muted">{!! $field->descr !!}</small>@endif
                                </label>
                                <div class="col-sm-5">
                                    {!! $field->input !!}
                                    @if ($errors->has($field->name))<small class="form-text text-muted">{{ $errors->first($field->name) }}</small>@endif
                                </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
</div>

<!-- SUBMIT Form -->
<div class="card">
	<div class="card-footer">
		<div class="row">
            <div class="col-sm-5 offset-md-7">
    			<button type="submit" title="Ctrl+S" class="btn btn-outline-success">
    				<span class="d-md-none"><i class="fa fa-floppy-o"></i></span>
    				<span class="d-none d-md-inline">@lang('btn.save')</span>
    			</button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-dark">
                    <span class="d-lg-none"><i class="fa fa-ban"></i></span>
                    <span class="d-none d-lg-inline">@lang('btn.cancel')</span>
                </a>
                @can ('admin.settings.modify')
                    <a href="{{ route('admin.settings.create', ['module_name' => $module->name]) }}" class="btn btn-outline-dark">
        				<span class="d-lg-none"><i class="fa fa-plus"></i></span>
        				<span class="d-none d-lg-inline">@lang('btn.create')</span>
                    </a>
                @endcan
    		</div>
		</div>
	</div>
</div>
