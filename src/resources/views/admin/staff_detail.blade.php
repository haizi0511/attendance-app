@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/staff_detail.css') }}">
@endsection

@section('content')
<header class="admin-header">
    <div class="admin-header__left">
        <img src="{{ asset('img/COACHTECHヘッダーロゴ.png') }}" alt="COACHTECH" class="admin-header__logo">
    </div>

    <ul class="admin-header__nav">
        <li><a href="/admin/attendance/list">勤怠一覧</a></li>
        <li><a href="/admin/staff/list">スタッフ一覧</a></li>
        <li><a href="/admin/attendance_request">申請一覧</a></li>
        <li>
            <form action="/logout" method="post">
                @csrf
                <button class="logout-btn">ログアウト</button>
            </form>
        </li>
    </ul>
</header>

<div class="staff-detail">

    <h1 class="staff-detail__title">
        {{ $user->name }} さんの勤怠
    </h1>

    <div class="month-switch">
        <a href="{{ route('admin.staff.detail', ['id' => $user->id, 'month' => $prevMonth]) }}" class="month-switch__arrow">← 前月</a>

        <span class="month-switch__current">
            {{ $currentMonth->format('Y年 n月') }}
        </span>

        <a href="{{ route('admin.staff.detail', ['id' => $user->id, 'month' => $nextMonth]) }}" class="month-switch__arrow">翌月 →</a>
    </div>
    <table class="staff-detail__table">
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
                    <td>{{ $attendance->created_at->format('n月 j日') }}</td>
                    <td>{{ optional($attendance->clock_in)->format('H:i') ?? '' }}</td>
                    <td>{{ optional($attendance->clock_out)->format('H:i') ?? '' }}</td>
                    <td>{{ $attendance->total_break_time ?? '0:00' }}</td>
                    <td>{{ $attendance->total_working_hours ?? '0:00' }}</td>

                    <td>
                        <a href="{{ route('attendance.detail', $attendance->id) }}" class="detail-link">詳細</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="csv-area">
        <a href="{{ route('admin.staff.csv', ['id' => $user->id, 'month' => $currentMonth->format('Y-m')]) }}"
        class="csv-btn">
            CSV出力
        </a>
    </div>

</div>

@endsection
