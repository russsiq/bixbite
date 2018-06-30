<aside class="widget widget-notes clearfix">
    <div class="widget-body">
        <form action="" method="post" accept-charset="UTF-8">
            @csrf
            <table class="table notes-list">
                <tbody>
                @forelse ($widget->items as $key => $note)
                    <tr class="notes-item {{ ! $note->is_completed ? 'warning' : 'success' }}">
                        <td>
                            <a href="{{ route('admin.notes.edit', $note) }}" class="notes-title text-dark">{{ $note->title }}</a>
                            <p class="notes-description text-muted">{!! $note->description !!}</p>
                        </td>
                        <td>{{ $note->created }}</td>
                        <td class="text-right">
                            <button type="submit" title="@lang('btn.is_completed')" class="btn btn-link text-warning"
                                formaction="{{ route('admin.notes.update', $note) }}"
                                name="_method" value="PUT"><i class="fa fa-line-chart"></i></button>
                        </td>
                    </tr>
                @empty
                    <tr class="notes-item">
                        <td>
                            <p class=" mb-0">
                            <a href="{{ route('admin.notes.index') }}" class="btn btn-outline-primary">Перейти к заметкам &raquo;</a>
                            </p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </form>
    </div>
</aside>

@push('css')
    <style>
        .notes-list {
            box-shadow: 0 8px 12px 0 rgba(0, 0, 0, 0.25);
            font-size: .78rem;
            background: linear-gradient(to right, #fcfcfc 0%, #f1f1f1 80%, rgb(0, 140, 186, .2) 100%);
            margin-bottom: 0;
        }
        .notes-list .notes-item td {
            border-top: 0;
        }
        .notes-list .notes-item td:first-of-type {
            padding-left: 1.25rem;
        }
        .notes-list .notes-item td:first-of-type:before {
            content: ' ';
            position: absolute;
            border-left: .1rem solid #43ac6a;
            height:100%;
            left: 0;
            top: 0;
        }
        .notes-list .notes-item.warning td:first-of-type:before {
            border-left-color: #e99002;
        }
        .notes-list .notes-title {
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .notes-list .notes-description {
            margin: 0;
        }
    </style>
@endpush
