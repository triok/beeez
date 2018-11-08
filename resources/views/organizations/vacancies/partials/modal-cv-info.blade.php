@push('modals')
    <div class="modal fade" id="modal-cv-{{ $cv->id }}" tabindex="-1" role="dialog"
         aria-labelledby="modalCategoriesLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="title">
                        Инфнормация об отклике
                    </h4>
                </div>

                <div class="modal-body">
                    <p>
                        <b>ФИО:</b>
                        {{ $cv->name }}
                    </p>

                    <p>
                        <b>Email:</b>
                        {{ $cv->email }}
                    </p>

                    <p>
                        <b>Телефон:</b>
                        {{ $cv->phone }}
                    </p>

                    <p>
                        <b>Расскажите о себе:</b>
                        {{ $cv->about }}
                    </p>

                    <p>
                        <b>Файл для скачивания:</b>
                        @if ($file = $cv->files()->first())
                        <a target="_blank" href="{{ $file->link() }}">{{ $file->title }}</a>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
@endpush