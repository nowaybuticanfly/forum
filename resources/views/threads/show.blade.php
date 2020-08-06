@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <h5 class="card-header">
                        <div class="level">
                            <span class="flex">
                            <a href="/profiles/{{$thread->creator->name}}">{{ $thread->creator->name }}</a> posted:
                            {{ $thread->title }}
                            </span>
                            @can('delete', $thread)
                            <form method="POST" action="{{$thread->path()}}">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-link">Delete Thread</button>
                            </form>
                            @endcan
                        </div>
                    </h5>
                    <div class="card-body">

                        {{ $thread->body }}
                    </div>
                </div>

                @foreach ($replies as $reply)
                    @include ('threads.reply')
                @endforeach

                {{ $replies->links() }}

                @if (auth()->check())
                    <form method="POST" action="{{ $thread->path() . '/replies' }}" class="mt-2">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <textarea name="body" id="body" class="form-control" placeholder="Have something to say?"
                                      rows="5"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Post</button>
                    </form>
                @else
                    <p class="text-center">Please <a href="{{ route('login') }}">sign in</a> to participate in this
                        discussion.</p>
                @endif
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <p>
                            This thread was published {{ $thread->created_at->diffForHumans() }} by
                            <a href="/profiles/{{$thread->creator->name}}">{{ $thread->creator->name }}</a>, and currently
                            has {{ $thread->replies_count }} {{ Str::plural('comment', $thread->replies_count) }}.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
