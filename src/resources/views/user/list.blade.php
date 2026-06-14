@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user/list.css') }}">
@endsection

@section('content')
<header class="user-header">
    <div class="user-header__left">
        <img src="{{ asset('img/COACHTECHヘッダーロゴ.png') }}" alt="COACHTECH" class="user-header__logo">
    </div>

    <ul class="user-header__nav">
        <li><a href="/">勤怠</a></li>
        <li><a href="/attendance/list">勤怠一覧</a></li>
        <li><a href="/attendance_request/list">申請</a></li>
        <li>
        <form action="/logout" method="post">
            @csrf
        <button class="logout-btn">ログアウト</button>
        </form>
        </li>
    </ul>
</header>
<div class="attendance">
    <h1 class="attendance__title">勤怠一覧</h1>

    {{-- 月移動 --}}
    <div class="attendance__month-nav">
        <a href="{{ route('attendance.list', ['month' => $prevMonth]) }}" class="attendance__month-link">← 前月</a>
        <span class="attendance__month-text">{{ $currentMonth->format('Y/m') }}
        </span>
        <a href="{{ route('attendance.list', ['month' => $nextMonth]) }}" class="attendance__month-link">翌月 →</a>
    </div>

    <table class="attendance__table">
        <thead>
            <tr>
                <th>日付</th>
                <th>出勤</th>
                <th>退勤</th>
                <th>休憩</th>
                <th>合計</th>
                <th>詳細</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->created_at->format('m/d') }}({{ ['日','月','火','水','木','金','土'][$attendance->created_at->dayOfWeek] }})</td>
                    <td>{{ $attendance->clock_in->format('H:i') }}</td>
                    <td>{{ $attendance->clock_out->format('H:i') }}</td>
                    <td>{{ $attendance->total_break_time ?? '0:00' }}</td>
                    <td>{{ $attendance->work_total ?? '0:00' }}</td>

                    <td>
                        <a href="{{ route('attendance.detail', $attendance->id) }}" class="attendance__detail-link">
                            詳細
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
