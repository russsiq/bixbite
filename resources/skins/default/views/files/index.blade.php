@extends('layouts.app')

@section('breadcrumb')
    @component('components.breadcrumbs')
        @slot('crumbs', [
            ['action' => 'files.index', 'title' => 'filemanager'], $filetype ? ['action' => null, 'title' => 'filetype-' . $filetype] : [],
        ])
    @endcomponent
@endsection

@section('mainblock')
	<div class="card d-print-none">
		<div class="card-header d-flex">
            @can ('admin.files.create')
                <a href="{{ route('admin.files.create') }}" class="btn btn-outline-dark"><i class="fa fa-plus"></i>  @lang('btn.create')</a>
            @endcan
            <div class="btn-group d-flex ml-auto">
                @can ('admin.settings.details')
                    <a href="{{ route('admin.settings.module', 'files') }}" title="@lang('settings')" class="btn btn-outline-dark"><i class="fa fa-cogs"></i></a>
                @endcan
			</div>
			<div class="btn-group ml-auto">
				{{-- <button type="button" title="@lang('btn.filter')" class="btn btn-outline-dark" data-toggle="collapse" data-target="#files_filter"><i class="fa fa-filter"></i></button> --}}
				<button type="button" title="@lang('btn.print')" class="btn btn-outline-dark" onclick="window.print();"><i class="fa fa-print"></i></button>
			</div>
		</div>
    </div>

    <div class="card card-filemanager">
        <div class="card-header">
            <ul class="nav d-flex" role="tablist">
                <li class="nav-item mr-auto">
                    <a href="#upload_pane" class="btn btn-link" data-toggle="tab"><i class="fa fa-upload"></i> @lang('upload-pane')</a>
                </li>
                @foreach ([
                        ['name' => 'image', 'icon' => 'fa-file-image-o'], ['name' => 'audio', 'icon' => 'fa-file-audio-o'], ['name' => 'video', 'icon' => 'fa-file-video-o'],
                        ['name' => 'document', 'icon' => 'fa-file-text-o'], ['name' => 'archive', 'icon' => 'fa-file-archive-o'], ['name' => 'other', 'icon' => 'fa-files-o'],
                    ] as $type)
                    <li class="nav-item">
                        <a href="{{ route('admin.files.index', ['filetype' => $type['name']]) }}" class="btn btn-link">
                            <i class="fa {{ $type['icon'] }}"></i> <span class="d-none d-md-inline-block">@lang('filetype-'.$type['name'])</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>


        <!-- Main content form -->
        <form id="form_action" name="mass_update" action="" method="post" accept-charset="UTF-8" class="form-horizontal">
            @csrf

            <!-- List of files: BEGIN -->
            <div class="tab-content">

                <div id="upload_pane" class="tab-pane fade" role="tabpanel">
                    <div class="card-body">
                        <p class="alert alert-info mb-4">@lang('upload-pane#descr')</p>
                        <upload-files :post_url="'{{ route('admin.files.upload') }}'" :multiple="true"></upload-files>
                    </div>
                </div>

                <div class="tab-pane fade show active">
                    <div class="card-body">
                        @empty ($files->count())
                            @lang('common.msg.not_found')
                        @else
                            <div class="card-columns baguetteBox">
                                @foreach ($files as $key => $file)
                                    <div class="card card-file">

                                        <button
                                            type="submit"
                                            title="@lang('btn.delete')"
                                            class="btn btn-link text-danger pull-right"
                                            style="position: absolute; right: 0; opacity: .8; text-shadow: 0 0 2px #000;"
                                            onclick="return confirm('@lang('msg.sure')');"
                                            formaction="{{ route('admin.files.delete', $file) }}"
                                            name="_method" value="DELETE"
                                            ><i class="fa fa-trash-o"></i></button>

                                        @if ('image' == $file->type)
                                            <a class="lightbox" href="{{ $file->url }}">
                                                <img class="card-img-top" src="{{ $file->getUrlAttribute('thumb') ?? $file->url }}" alt="{{ $file->name }}" title="{{ $file->title }}">
                                            </a>
                                        @elseif ('audio' == $file->type)
                                            <div class="card-icon-top"><i class="fa fa-music"></i></div>
                                            <audio preload="none" controls="controls" style="object-fit: scale-down; width: 100%; margin-bottom: -6px;"><source type="audio/mpeg" src="{{ $file->url }}"/></audio>
                                        @elseif ('video' == $file->type)
                                            {{-- <div class="card-icon-top"><i class="fa fa-film"></i></div> --}}
                                            <video preload="none" controls="controls" style="object-fit: scale-down; width: 100%; margin-bottom: -6px;"><source type="video/mp4" src="{{ $file->url }}"/></video>
                                        @elseif ('archive' == $file->type)
                                            <div class="card-icon-top"><i class="fa fa-archive"></i></div>
                                        @else
                                            <div class="card-icon-top"><i class="fa fa-file"></i></div>
                                        @endif
                                        <div class="card-body">
                                            <h6 class="card-title">{{--#{{ $file->id }}--}} <a href="{{ route('admin.files.edit', $file) }}" class="">{{ $file->title }}</a> [{{ $file->extension }}]</h6>
                                            <p class="card-text">{!! $file->description !!}</p>
                                            <p class="card-text"><small class="text-muted">{{ $file->created_at }}</small></p>
                                        </div>
                                    </div>
                                @endforeach
        	                </div>
                        @endif
                    </div>

                    @if ($files->hasPages())
            			<div class="card-footer">
            				<div class="row">
            					<div class="col ofset-md-4 text-right">
            						{{ $files->links('components.pagination') }}
            					</div>
            				</div>
            			</div>
                    @endif
                </div>
            </div>
        </form>
	</div>
@endsection


@push('css')

    <style type="text/css">
        @media (min-width: 576px) {
            .card-filemanager .card-columns {
                column-count: 4;
            }
        }

        .card-file .card-icon-top {
            font-size: 70px;
            line-height: 1;
            color: #dadada;
            padding: .75rem 1.25rem;
            text-align: center;
        }

        .card-file .card-body {
            background-color: #f8f8f8;
            border-top: 1px solid #e7eaec;
        }
    </style>
@endpush

@push('scripts')
    <!-- List of vendor: BEGIN -->
    <script src="{{ skin_asset('js/libsuggest.js') }}"></script>
    <!-- List of vendor: END -->

    <script>
    baguetteBox.run('.baguetteBox', {
        noScrollbars: true,
        // buttons: false,
        captions: function(element) {
            return element.getElementsByTagName('img')[0].title;
        }
    });
    </script>
@endpush
