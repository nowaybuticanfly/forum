@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{$thread->title}}</div>

                    <div class="card-body">
                        {{$thread->body}}
                    </div>

                </div>
            </div>
        </div>


        <div class="row justify-content-center">
            <div class="col-md-8">
                @foreach($thread->replies as $reply)
                <div class="card">
                    @include('threads.reply')
                @endforeach
            </div>
        </div>

        @if(auth()->check())
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <form  method="POST" action="{{'/threads/' . $thread->id . '/replies'}}">
                        @csrf

                        <div class="form-group">
                            <textarea name="body" id="body" class="form-control  mt-3"placeholder="Have something to say?"></textarea>
                        </div>
                        <button type="submit" class="btn btn-default">Post</button>
                    </form>
                </div>
        @else
            <div class="row justify-content-center">
                <p>                <a href="{{route('login')}}">Login </a> to post a reply</p>
            </div>
        @endif
    </div>
@endsection
