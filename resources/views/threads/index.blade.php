@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @forelse ($threads as $thread)

                    <div class="card mb-3">
                        <div class="card-header">
                            <div class="level">
                                <h4 class="flex">
                                    <a href="/profiles/{{$thread->creator->name}}">{{ $thread->creator->name }}</a> posted:
                                    <a href="{{$thread->path()}}">{{ $thread->title }}</a>
                                </h4>

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

            </div>
            </div>
        </div>
    </div>
@endsection
