@extends('layouts.app')

@section('breadcrumb')
    @component('components.breadcrumbs')
        @slot('crumbs', [
            ['action' => 'themes.index', 'title' => 'themes'], ['action' => 'themes.templates', 'title' => 'code-editor'],
        ])
    @endcomponent
@endsection

@section('mainblock')
    <nav class="navbar navbar-expand navbar-dark bg-primary justify-content-between">
        <a href="#" class="navbar-brand">{{ $theme }}</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#bs-navbar"><span class="navbar-toggler-icon"></span></button>
        <div id="bs-navbar" class="collapse navbar-collapse">
            {{-- <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="#" class="nav-link" data-toggle="modal" data-target="#hotkeys">@lang('help')</a>
                </li>
            </ul> --}}
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    {{-- <a href="#" title="Fullscreen (F11)" onclick="return false" class="nav-link"><i class="fa fa-arrows-alt"></i></a> --}}
                    <a href="#" class="nav-link" data-toggle="modal" data-target="#hotkeys">@lang('help')</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main content form -->
    <form action="{{ route('admin.templates.store') }}" method="post">
        @csrf
        <div class="container-fluid">
            <div class="row">
                <nav class="col-md-3 bg-light sidebar">
                    <ul class="tree-view__list">
                        @isset ($templates)
                            {{-- DO NOT use "@each(...)", because "$loop->..." does not work --}}
                            @include('themes.templates.partials.templates', ['items' => $templates])
                        @endisset
                </nav>

                <main role="main" class="col-md-9 ml-sm-auto p-3 border-bottom border-right">
                    <div class="form-group {{ $errors->has('template') ? ' has-error' : '' }}">
                        <div class="input-group">
                            <input type="text" name="template" value="" class="form-control" placeholder="@lang('title.selecte')" autocomplete="off" required />
                            <div class="input-group-append">
                                <input type="submit" value="@lang('btn.create')" class="btn btn-outline-primary" />
                                <button type="submit" class="btn btn-outline-danger"
                                    onclick="return confirm('@lang('msg.sure')');"
                                    formaction="{{ route('admin.templates.delete', ':template') }}"
                                    name="_method" value="DELETE">
                                    @lang('btn.delete')
                                </button>
                            </div>
                        </div>
                        <small class="template__title form-text">{{ now() }}</small>
                    </div>

                    <div class="form-group border">
                        <textarea id="codeEditor" name="content" rows="12" class="form-control"></textarea>
                    </div>
                    <div class="for-m-group">
                        <input id="template__save" type="button" value="@lang('btn.save')" class="btn btn-outline-success" />
                    </div>
                </main>
            </div>
        </div>
    </form>

    @push('scripts')
        <script>
            var template = false;

            $(document).on('click', '[data-path]', function(e) {
                template = this.getAttribute('data-path');
                e.preventDefault();
                codeEditor_refresh();
            });

            codeEditor_refresh = function() {
                var loader = LoadingLayer.show({active: true});

                axios({
                    method: 'get',
                    url: "{{ route('admin.templates.edit', ':template') }}",
                    params: {
                        template: template
                    }
                })
                .then(function (response) {
                    $('[name="template"]').val(template);
                    $('.template__title').html(response.data.file.size+'; '+response.data.file.modified);
                    $('.CodeMirror').remove();
                    $('#codeEditor').val(response.data.file.content);

                    let tmode;
                    if (template.indexOf(".ini") > 0) tmode = "text/x-ini";
                    if (template.indexOf(".tpl") > 0) tmode = "text/html";
                    if (template.indexOf(".html") > 0) tmode = "text/html";
                    if (template.indexOf(".css") > 0) tmode = "text/x-gss";
                    if (template.indexOf(".js") > 0) tmode = "javascript";
                    if (template.indexOf(".php") > 0) tmode = "php";

                    let editor = CodeMirror.fromTextArea(
                        document.getElementById('codeEditor'),
                        {
                            lineNumbers: true,
                            mode: tmode,
                            // profile: 'xhtml',
                            lineWrapping: true,
                            styleActiveLine: true,
                            indentUnit: 4,
                            tabMode: 'shift',
                            enterMode: 'indent',
                            theme: 'default',
                            extraKeys: {
                                "F11": function(editor) {editor.setOption("fullScreen", ! editor.getOption("fullScreen"));},
                                "Esc": function(editor) {if (editor.getOption("fullScreen")) editor.setOption("fullScreen", false);},
                                "Tab": "indentMore",
                                "Shift-Tab": "indentLess",
                            }
                    });
                    editor.on("change", function(editor) {
                        $("#codeEditor").val(editor.getValue());
                    });
                    $('.CodeMirror').css({
                        // 'font-size': '1rem',
                        'height': '100%'
                    });
                    emmetCodeMirror(editor);
                    loader.hide();
                })
                .catch(function (error) {
                    console.log(error);
                    if (error.response.status === 422) {
                        for(var k in error.response.data.errors) {
                            Notification.warning({message: error.response.data.errors[k][0]});
                        }
                    } else {
                        Notification.error({message: error.response.data.message});
                    }
                    loader.hide();
                });
            }

            $(document).on('click', '#template__save', function(e) {
                if(template.length > 0) {
                    codeEditor_update();
                } else {
                    Notification.warning({message: '@lang('msg.not_selected')'});
                }
                e.preventDefault();
            });

            document.onkeydown = function(e) {
                e = e || event;
                if (e.ctrlKey && e.keyCode == 'S'.charCodeAt(0)) {
                    if(template.length > 0) {
                        codeEditor_update();
                    } else {
                        Notification.warning({message: '@lang('msg.not_selected')'});
                    }
                    e.preventDefault();
                }
            }

            codeEditor_update = function() {
                var loader = LoadingLayer.show();

                axios({
                    method: 'post',
                    url: "{{ route('admin.templates.update', ':template') }}",
                    params: {
                        template: template,
                        content: $('#codeEditor').val(),
                        _method: "PUT"
                    }
                })
                .then(function (response) {
                    if (response.data.status === false) {
                        Notification.info({message: response.data.message});
                    } else {
                        $('.template__title').html(response.data.file.size+'; '+response.data.file.modified);
                        Notification.success({message: response.data.message});
                    }
                    loader.hide();
                })
                .catch(function (error) {
                    console.log(error);
                    if (error.response.status === 422) {
                        for(var k in error.response.data.errors) {
                            Notification.warning({message: error.response.data.errors[k][0]});
                        }
                    } else {
                        Notification.error({message: error.response.data.message});
                    }
                    loader.hide();
                });
            }
        </script>
    @endpush

    @push('css')
    <style>
        .tree-view__list {
            overflow: hidden;
            margin: 0;
            padding: .25rem 0;
        }
        .tree-view__item {
            background: transparent;
            display: block;
            margin-bottom: -1px;
            padding: .25rem;
        }
        .tree-view__subitem {
            background: transparent;
            margin-left: 0.5rem;
            margin-bottom: -1px;
            padding: 0 .25rem;
        }
        .tree-view__link {
            white-space: nowrap;
            text-overflow: ellipsis;
            display: block;
            overflow: hidden;
        }
        .tree-view__link:hover {
            text-decoration: none;
            color: #222;
        }
    </style>
    @endpush

    <div id="hotkeys" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>@lang('help')</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <h4>Сочетания клавиш codemirror</h4>
                    <div class="row">
                        <div class="col col-xs-6">
                            <table class="table table-sm"><tbody>
                                <tr><td><kbd>Ctrl + S</kbd></td><td>сохранить шаблон</td></tr>
                                <tr><td><kbd>F11</kbd></td><td>полноэкранный режим</td></tr>
                                <tr><td><kbd>Tab</kbd></td><td>развернуть аббревиатуру (<a href="http://docs.emmet.io/cheat-sheet/" target="_blank" title="Emmet cheat sheet" class="">emmet</a>)</td></tr>
                            </tbody></table>
                        </div>
                        <div class="col col-xs-6">
                            <table class="table table-sm"><tbody>
                                <tr><td><kbd>Ctrl + F</kbd></td><td>начать поиск</td></tr>
                                <tr><td><kbd>Ctrl + G</kbd></td><td>найти далее</td></tr>
                                <tr><td><kbd>Shift + Ctrl + F</kbd></td><td>заменить</td></tr>
                            </tbody></table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="cancel" class="btn btn-secondary" data-dismiss="modal">@lang('btn.close')</button>
                </div>
            </div>
        </div>
    </div>
@endsection
