<reply :reply="{{$reply}}" inline-template v-cloak>
    <div class="card mt-3" id="reply-{{$reply->id}}">
        <h6 class="card-header">
            <div class="level">
                <h5 class="flex">
                <a href="/profiles/{{$reply->owner->name}}">
                    {{ $reply->owner->name }}
                </a>
                said {{ $reply->created_at->diffForHumans() }}...

                </h5>
                <div>
                    <form method="POST" action="/replies/{{$reply->id}}/favorites">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary"{{$reply->isFavorited() ? 'disabled' : ''}} >
                                {{$reply->favorites_count}} {{Str::plural('favorite', $reply->favorites_count)}}
                        </button>
                    </form>
                </div>

            </div>
        </h6>

        <div class="card-body">
            <div v-if="editing">
                <textarea class="form-control" v-model="body"></textarea>

                <button class="btn btn-primary btn-sm" @click="update">Update</button>
                <button class="btn btn-link btn-sm" @click="editing=false">Cancel</button>
            </div>
            <div v-else v-text="body">
            </div>
        </div>

        @can('delete', $reply)
            <div class="card-footer level">
                <button class="btn btn-primary btn-sm mr-3" @click="editing = true">Edit</button>
                <form method="POST" action="/replies/{{$reply->id}}">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </div>
        @endcan
    </div>
</reply>
