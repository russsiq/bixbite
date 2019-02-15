<a href="{{ route('file.download', $file->id) }}" class="btn btn-outline-dark" download>Скачать {{ $file->filesize }}</a>

{{--
    Variables:
     * $file->filesize - shows the size of a file in human readable format in bytes to kb, mb, gb, tb.
     * $file->downloads - counter of downloads file

    Additionally: https://developer.mozilla.org/en-US/docs/Web/HTML/Element/a#attr-download
--}}
