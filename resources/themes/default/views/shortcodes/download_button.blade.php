<a href="{{ route('attachment.download', $attachment->id) }}" class="btn btn-outline-dark" download>Скачать {{ $attachment->filesize }}</a>

{{--
    Variables:
     * $attachment->filesize - shows the size of a file in human readable format in bytes to kb, mb, gb, tb.
     * $attachment->downloads - counter of downloads file

    Additionally: https://developer.mozilla.org/en-US/docs/Web/HTML/Element/a#attr-download
--}}
