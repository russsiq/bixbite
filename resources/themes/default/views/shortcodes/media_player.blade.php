@if ('audio' == $media->type)
    <audio preload="none" src="{{ $media->url }}" style="object-fit: scale-down; width: 100%;" controls></audio>
@elseif ('video' == $media->type)
    <video preload="none" src="{{ $media->url }}" style="object-fit: scale-down; width: 100%;" controls></video>
@else
    <div class="alert">This media type not supported.</div>
@endif
@if ($media->description)
    <blockquote>{{ $media->description }}</blockquote>
@endif

{{-- Variables: id, url, title, description, type, mime_type, --}}
{{-- <iframe width="560" height="315" src="{{ $url }}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> --}}
