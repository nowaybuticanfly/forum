<div class="card mt-3">
    <h6 class="card-header">
        <a href="#">
            {{ $reply->owner->name }}
        </a>
        said {{ $reply->created_at->diffForHumans() }}...
    </h6>
    <div class="card-body">


        {{ $reply->body }}
    </div>
</div>
