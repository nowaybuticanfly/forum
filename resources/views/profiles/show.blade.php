@extends('layouts.app')


@section('content')
    <div class="container">
        <div class="pb-2 mt-4 mb-2 border-bottom">
            <h1>
                {{ $profileUser->name }}
                <small>Since {{ $profileUser->created_at->diffForHumans() }}</small>
            </h1>

            @can ('update', $profileUser)
                <form method="POST" action="/api/users/{{$profileUser->name}}/avatar" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="avatar">
                    <button type="submit" class="btn btn-primary btn-sm">Add Avatar</button>
                </form>
            @endcan
            <img src="{{ $profileUser->avatar()  }}" alt="avatar" width="100" height="100">
        </div>

        @forelse($activities as $date => $dailyActivities)
            <h3 class="page-header">
                {{ $date }}
            </h3>

            @foreach($dailyActivities as $activity)
                @if(view()->exists("profiles.activities.{$activity->type}"))
                    @include("profiles.activities.{$activity->type}")
                @endif
            @endforeach
        @empty
            <p>There is no activity for this user yet.</p>
        @endforelse

    </div>



@endsection
