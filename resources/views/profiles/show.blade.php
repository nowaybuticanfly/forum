@extends('layouts.app')


@section('content')
    <div class="container">
        <div class="pb-2 mt-4 mb-2 border-bottom">
            <h1>
                {{ $profileUser->name }}
                <small>Since {{ $profileUser->created_at->diffForHumans() }}</small>
            </h1>
        </div>

        @foreach($threads as $thread)
            <div class="card mt-2">
                <h5 class="card-header">
                    <div class="level">
                    <span class="flex">
                        <a href="/profiles/{{$thread->creator->name}}">{{ $thread->creator->name }}</a> posted:
                        <a href="{{$thread->path()}}">{{ $thread->title }}</a>
                    </span>
                        <span>
                        {{ $thread->created_at->diffForHumans() }}
                    </span>

                    </div>
                </h5>
                <div class="card-body">

                    {{ $thread->body }}
                </div>
            </div>
        @endforeach

    </div>

    {{ $threads->links() }}


@endsection
