@extends('layouts.app')

@section('header')
    <link href="{{ asset('css/vendor/tribute.css') }}" rel="stylesheet">
@endsection

@section('content')
    <thread-view :initial-replies-count="{{ $thread->replies_count }}" inline-template>
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <h5 class="card-header">
                            <div class="level">
                                <img src="{{ $thread->creator->avatar()}}" alt="avatar" height="50" width="50">
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


                    <replies @removed="repliesCount--"
                             @added="repliesCount++"></replies>


                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <p>
                                This thread was published {{ $thread->created_at->diffForHumans() }} by
                                <a href="/profiles/{{$thread->creator->name}}">{{ $thread->creator->name }}</a>, and currently
                                has <span v-text="repliesCount"></span> {{ Str::plural('comment', $thread->replies_count) }}.
                            </p>
                            <subscribe-button :active="{{ json_encode($thread->isSubscribedTo) }}"></subscribe-button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </thread-view>
@endsection
