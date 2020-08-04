<div class="card mt-3">
    <h6 class="card-header">
        <div class="level">
            <h5 class="flex">
            <a href="#">
                {{ $reply->owner->name }}
            </a>
            said {{ $reply->created_at->diffForHumans() }}...

            </h5>
            <div>
                <form method="POST" action="/replies/{{$reply->id}}/favorites">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary disabled" disabled{{$reply->isFavorited() ? 'disabled' : ''}}">
                        {{$reply->favorites_count}} {{Str::plural('favorite', $reply->favorites_count)}}
                    </button>
                </form>
            </div>

        </div>
    </h6>
    <div class="card-body">


        {{ $reply->body }}
    </div>
</div>
