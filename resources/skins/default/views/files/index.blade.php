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

    <div class="card card-table">
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
                        <div class="upload-files__outer" style="padding: 0 1.25rem 1.25rem 1.25rem">
                            <upload-files :post_url="'{{ route('admin.files.upload') }}'" :multiple="true"></upload-files>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade show active">
                    <div class="card-body table-responsive">
                        @empty ($files->count())
                            @lang('common.msg.not_found')
                        @else
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th colspan="2">@lang('title')</th>
                                        <th class="text-right"></th>
                                        <th>@lang('attaching')</th>
                                        <th class="text-right d-print-none">@lang('action')</th>
                                    </tr>
                                    <tbody>
                                    @foreach ($files as $key => $file)
                                        <tr>
                                            <td>{{ $file->id }}</td>
                                            <td class="baguetteBox">
                                                @if ('image' == $file->type)
                                                <a class="lightbox" href="{{ $file->url }}">
                                                    <img src="{{ $file->getUrlAttribute('thumb') ?? $file->url }}" alt="{{ $file->title }}" title="{{ $file->title }}" class="card-file-icon" width="42" />
                                                </a>
                                                @elseif ('audio' == $file->type)
                                                <a href="#mediaModal" class="media-link" data-toggle="modal" data-src="{{ $file->url }}" data-title="{{ $file->title }}" data-type="audio"><div class="card-file-icon"><i class="fa fa-music"></i></div></a>
                                                @elseif ('video' == $file->type)
                                                <a href="#mediaModal" class="media-link" data-toggle="modal" data-src="{{ $file->url }}" data-title="{{ $file->title }}" data-type="video"><div class="card-file-icon"><i class="fa fa-film"></i></div></a>
                                                @elseif ('archive' == $file->type)
                                                <div class="card-file-icon"><i class="fa fa-archive"></i></div>
                                                @else
                                                <div class="card-file-icon"><i class="fa fa-file"></i></div>
                                                @endif
                                            </td>
                                            <td>{{ $file->title }}</td>
                                            <td>{{ $file->extension }}</td>
                                            <td>
                                                @if ($file->attachment)
                                                    <a href="{{ $file->attachment->url }}" target="_blank">{{ $file->attachment->title }}</a>
                                                @else
                                                    @lang('not_attached')
                                                @endif
                                            </td>
                                            <td class="text-right d-print-none">
                                                <div class="btn-group">
                                                    <a href="{{ $file->url }}" target="_blank" class="btn btn-link"><i class="fa fa-external-link"></i></a>

                                                    @can ('admin.files.update', $file)
                                                        <a href="{{ route('admin.files.edit', $file) }}" class="btn btn-link"><i class="fa fa-pencil"></i></a>
                                                    @endcan

                                                    @can ('admin.files.delete', $file)
                                                        <button type="submit" class="btn btn-link"
                                                            onclick="return confirm('@lang('msg.sure')');"
                                                            formaction="{{ route('admin.files.delete', $file) }}"
                                                            name="_method" value="DELETE">
                                                            <i class="fa fa-trash-o text-danger"></i>
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-link text-muted" disabled><i class="fa fa-trash-o"></i></button>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </thead>
                            </table>
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


<!-- Modal -->
<div id="mediaModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Html5 super mega modal player</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <video preload="none" controls="controls" src="" style="object-fit: scale-down; width: 100%;" class="d-none"></video>
                <audio preload="none" controls="controls" src="" style="object-fit: scale-down; width: 100%;" class="d-none"></audio>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('btn.close')</button>
                {{-- <button type="button" class="btn btn-primary">@lang('btn.download')</button> --}}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    baguetteBox.run('.baguetteBox', {
        noScrollbars: true,
        captions: function(element) {
            return element.getElementsByTagName('img')[0].title;
        }
    });
    $(document).ready(function() {
        var $mediaSrc, $mediaType, $mediaTitle;
        $('.media-link').click(function() {
            $mediaSrc = $(this).data('src');
            $mediaType = $(this).data('type');
            $mediaTitle = $(this).data('title');
        });
        $('#mediaModal').on('shown.bs.modal', function (e) {
            $('#mediaModal .modal-title').text($mediaTitle);
            $('#mediaModal ' + $mediaType).attr('src', $mediaSrc).addClass('d-block').removeClass('d-none');
        });
        $('#mediaModal').on('hide.bs.modal', function (e) {
            $('#mediaModal .modal-title').text('');
            $('#mediaModal ' + $mediaType).attr('src', '').removeClass('d-block').addClass('d-none');
        });
    });
</script>
@endpush
