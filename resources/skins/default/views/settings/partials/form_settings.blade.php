@if (count($sections) > 1)
    <ul class="nav nav-tabs" role="tablist">
        @foreach ($sections as $key => $section)
            <li class="nav-item {{ $loop->first ? 'active' : '' }}">
                <a  id="{{ $section }}-tab"
                    href="#{{ $section }}"
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
    @foreach ($sections as $key => $section)
        <div id="{{ $section }}" aria-labelledby="contact-tab" class="tab-pane {{ $loop->first ? 'show active' : '' }}" role="tabpanel">
            @foreach ($fieldsets as $key => $fieldset)
                <fieldset>
                    <legend>
                        @lang('legend.' . $fieldset)
                        {{-- @if ($field->toggle) <a href="#" title="@lang('fieldset.toggle')" class="adm-group-toggle"><i class="fa fa-caret-square-o-down"></i></a>@endif --}}
                    </legend>

                    @foreach ($fields as $key => $field)
                        @if ('html' == $field->type)
                            {{ $field->input }}
                        @else
                            <div class="form-group row {{ $errors->has($field->name) ? ' has-error' : '' }}">
                                <label class="col-sm-7 control-label">
                                    {{ $field->title }}
                                    @isset ($field->descr)<span class="form-text text-muted">{{ $field->descr }}</span>@endif
                                </label>
                                <div class="col-sm-5">
                                    {!! $field->input !!}
                                    @if ($errors->has($field->name))<span class="form-text text-muted">{{ $errors->first($field->name) }}</span>@endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                </fieldset>
            @endforeach
        </div>
    @endforeach
</div>

<input type="submit" name="submit" value="@lang('btn.save')" class="btn btn-outline-success" />

<script>
var form = document.getElementById('form_action');

// HotKeys to this page
document.onkeydown = function(e) {
    e = e || event;
    if (e.ctrlKey && e.keyCode == 'S'.charCodeAt(0)) {
        form.submit();
        return false;
    }
}
</script>
