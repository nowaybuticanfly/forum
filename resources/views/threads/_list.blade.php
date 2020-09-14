@forelse ($threads as $thread)
    <div class="card mb-3">
        <div class="card-header">
            <div class="level">
                <div class="flex">
                    <h4 class="flex">
                        <img src="{{ $thread->creator->avatar_path }}" alt="avatar" width="25" height="25" class="mr-1">
                        <a href="/profiles/{{$thread->creator->name}}">{{ $thread->creator->name }}</a> posted:
                        <a href="{{$thread->path()}}">
                            @if (auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                                <strong>{{ $thread->title }}</strong>
                            @else
                                {{ $thread->title }}
                            @endif
                        </a>
                    </h4>

                    <h5>Posted by <a href="/profiles/{{$thread->creator->name}}">{{$thread->creator->name}}</a></h5>
                </div>
                <strong>
                    <a href="{{$thread->path()}}">
                        {{$thread->replies_count}} {{Str::plural('reply', $thread->replies_count )}}
                    </a>
                </strong>
            </div>

        </div>

        <div class="card-body">
            <div class="body">{{$thread->body}}</div>
        </div>

    </div>
@empty
    No relevant results at the time.
@endforelse
