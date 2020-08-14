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

                <favorite :reply="{{$reply}}"></favorite>

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
                <button class="btn btn-danger btn-sm" @click="destroy">Delete</button>
            </div>
        @endcan
    </div>
</reply>
