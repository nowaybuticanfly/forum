@extends('layouts.app')


@section('content')
    <div class="container">
        <div class="pb-2 mt-4 mb-2 border-bottom">
            <h1>
                {{ $profileUser->name }}
                <small>Since {{ $profileUser->created_at->diffForHumans() }}</small>
            </h1>
        </div>

        @foreach($activities as $date => $dailyActivities)
            <h3 class="page-header">
                {{ $date }}
            </h3>

            @foreach($dailyActivities as $activity)
                @include("profiles.activities.{$activity->type}")
            @endforeach
        @endforeach

    </div>



@endsection
