@if ($errors->any())
    <section class="widget">
        <div class="widget__inner">
            <div class="widget__header">
                <h4 class="widget__title">{{ $title }}</h4>
            </div>
            <div class="widget__body">
                <div class="alert alert-danger">
                    <ul class="widget__list">
                        {{-- Отобразим ошибки валидации только собственнику сайта. --}}
                        @role('owner')
                            @foreach ($errors->all() as $error)
                                <li class="widget_item__content">{{ $error }}</li>
                            @endforeach
                        @else
                            <li class="widget_item__content">Не удалось отобразить виджет.</li>
                        @endrole
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endif
