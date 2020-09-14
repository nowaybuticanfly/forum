@extends('layouts.app')


@section('content')
    <div class="container">
        <div class="pb-2 mt-4 mb-2 border-bottom">
            <avatar-form :user="{{$profileUser}}"></avatar-form>
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
