@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user/attendance.css') }}">
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
<div class="attendance-container">
    <di class="attendance-card">
        {{-- ① ステータス --}}
        @include('user.status_text')

        {{-- ② 日付 --}} {{-- ③ 時刻 --}}
        <div id="date" class="date"></div>
                <div id="time" class="time"></div>

                <script>
                    function updateClock() {
                        const now = new Date();

                        // 日付
                        const year = now.getFullYear();
                        const month = now.getMonth() + 1;
                        const day = now.getDate();

                        // 曜日
                        const weekdays = ['日', '月', '火', '水', '木', '金', '土'];
                        const weekday = weekdays[now.getDay()];

                        // 時刻
                        const hour = String(now.getHours()).padStart(2, '0');
                        const minute = String(now.getMinutes()).padStart(2, '0');

                        // 表示
                        document.getElementById('date').textContent =
                            `${year}年${month}月${day}日（${weekday}）`;

                        document.getElementById('time').textContent =
                            `${hour}:${minute}`;
                    }

                    setInterval(updateClock, 1000);
                    updateClock();
                </script>

        {{-- ④ ボタン --}}
        @include('user.status_actions')

        @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
    </div>
</div>
@endsection
