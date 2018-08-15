<hr>

<h4>Add a new message</h4>

<form action="{{ route('messages.update', $thread->id) }}" method="post">
    {{ method_field('put') }}
    {{ csrf_field() }}
    <textarea name="message" id="message" required rows="3" class="form-control"></textarea>
    <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Send</button>
</form>