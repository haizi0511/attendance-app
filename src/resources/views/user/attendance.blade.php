@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user/attendance.css') }}">
@endsection

@include('user.header_button')

@section('content')
<div class="attendance-container">
    <div class="attendance-card">

    <div id="date" class="date"></div>
        <div id="time" class="time"></div>

        <div class="status">
            @if (!$attendance)
                <p class="status-text">勤務外</p>
        </div>
            @include('user.time')

                <form action="{{ route('clock_in') }}" method="POST">
                    @csrf
                    <button class="btn btn-primary">出勤</button>
                </form>
                @elseif ($attendance->status === 1)
                    <p class="status-text">出勤中</p>

                    <form action="{{ route('clock_out') }}" method="POST">
                        @csrf
                        <button class="btn btn-danger">退勤</button>
                    </form>

                    <form action="{{ route('break_in') }}" method="POST">
                        @csrf
                        <button class="btn btn--warning">休憩入</button>
                    </form>

                @elseif ($attendance->status === 2)
                    <p class="status-text">休憩中</p>

                    <form action="{{ route('break_out') }}" method="POST">
                        @csrf
                        <button class="btn btn--warning">休憩戻</button>
                    </form>

                @elseif ($attendance->status === 3)
                    <p class="status-text">退勤済</p>
            @endif
    </div>
</div>
@endsection
