<div class="card mb-3">
    <div class="card-header">
        <div class="row">
            <div class="col align-self-center">
                <a href="/profiles/{{ $reply->owner->name }}">{{ $reply->owner->name }}</a> said {{ $reply->created_at->diffForHumans() }}...
            </div>
            <div class="col-auto">
                <form method="POST" action="/replies/{{ $reply->id }}/favorites">
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-secondary" {{ $reply->isFavorited() ? 'disabled' : ''}}>
                        {{ $reply->favorites_count }} {{ str_plural('Favorite', $reply->favorites_count) }}
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="card-body">{{ $reply->body }}</div>
</div>