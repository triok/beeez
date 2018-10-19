@if(Auth::check() && $notification = Auth::user()->unreadNotifications()->first())
    @php
        $notification->markAsRead();

        $transformer = new \App\Transformers\NotificationTransformer();

        $notification = $transformer->transform(
            $notification->toArray()
        );
    @endphp

    <div class="modal fade" id="notification-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                    <h4 class="modal-title" id="title">
                        {{ $notification['title'] }}
                    </h4>
                </div>

                <div class="modal-body">
                    {!! $notification['message']  !!}
                </div>

                <div class="modal-footer">
                    @foreach($notification['actions'] as $action)
                        {!! Form::open(['url' => $action['route'], 'method'=>'post', 'style' => 'display: inline-block']) !!}
                        <input type="hidden" name="id" value="{{ $notification['id'] }}">
                        <input type="hidden" name="redirect" value="{{ request()->path() }}">

                        <button type="submit" class="btn btn-sm {{ $action['class'] }}">
                            {{ $action['title'] }}
                        </button>
                        {!! Form::close() !!}
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script type="application/javascript">
        $(document).ready(function () {
            $('#notification-modal').modal();
        });
    </script>
@endif