@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/index.css') }}">
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

<div class="attendance-container">

    <h2 class="attendance-title">{{ \Carbon\Carbon::parse($date)->format('Y年n月j日') }} の勤怠</h2>

    <div class="attendance-date-nav">
        <a href="{{ route('admin.index', ['date' => $prevDate]) }}" class="date-nav-link">← 前日</a>

        <div class="date-picker">
            <form action="{{ route('admin.index') }}" method="get">
                <input type="date" name="date" value="{{ $date }}" onchange="this.form.submit()">
            </form>
        </div>

        <a href="{{ route('admin.index', ['date' => $nextDate]) }}" class="date-nav-link">翌日 →</a>
    </div>

    <table class="attendance-table">
        <thead>
            <tr>
                <th>名前</th>
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
                <td>{{ $attendance->user->name }}</td>
                <td>{{ $attendance->clock_in->format('H:i') }}</td>
                <td>{{ optional($attendance->clock_out)->format('H:i') }}</td>
                <td>{{ $attendance->total_break_time ?? '0:00' }}</td>
                <td>{{ $attendance->work_total ?? '0:00' }}</td>
                <td>
                    <a href="{{ route('admin.detail', $attendance->id) }}" class="detail-link">詳細</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
