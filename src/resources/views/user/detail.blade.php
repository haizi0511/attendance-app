@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user/detail.css') }}">
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
<div class="detail">

    <h1 class="detail__title">勤怠詳細</h1>

    <div class="detail__box">

        <form action="{{ route('attendance.update', $attendance->id) }}" method="POST">
            @csrf

            <div class="detail__row">
                <div class="detail__label">名前</div>
                <div class="detail__value">{{ $attendance->user->name }}</div>
            </div>

            <div class="detail__row">
                <div class="detail__label">日付</div>
                <div class="detail__value">
                    {{ $attendance->created_at->format('Y年 n月 j日') }}
                </div>
            </div>

            <div class="detail__row">
                <div class="detail__label">出勤・退勤</div>
                <div class="detail__value">
                <input type="time" name="clock_in" value="{{ optional($attendance->clock_in)->format('H:i') ?? '' }}">
                〜
                <input type="time" name="clock_out" value="{{ optional($attendance->clock_out)->format('H:i') ?? '' }}">
                </div>
            </div>

            {{-- 休憩 --}}
            @php
                $breakCount = $attendance->breakTimes->count();
            @endphp

            @for ($i = 0; $i < $breakCount + 1; $i++)
                @php
                    $break = $attendance->breakTimes[$i] ?? null;
                @endphp

                <div class="detail__row">
                    <div class="detail__label">休憩{{ $i + 1 }}</div>
                    <div class="detail__value">
                        <input type="time" name="break_start[]" 
                            value="{{ optional(optional($break)->break_start)->format('H:i') ?? '' }}">
                        〜
                        <input type="time" name="break_end[]" 
                            value="{{ optional(optional($break)->break_end)->format('H:i') ?? '' }}">
                    </div>
                </div>
            @endfor

            @foreach ($errors->get("break_start.*") as $messages)
                @foreach ($messages as $msg)
                    <div class="error-message">{{ $msg }}</div>
                @endforeach
            @endforeach

                @error('clock_in')
                    <div class="error-message">{{ $message }}</div>
                @enderror

            <div class="detail__row">
                <div class="detail__label">備考</div>
                <div class="detail__value">
                    <textarea name="note">{{ old('note', $attendance->note) }}</textarea>
                    @error('note')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="detail__footer">
                <button type="submit" class="detail__edit-btn">修正</button>
            </div>
        </form>
    </div>
    @if (session('message'))
        <div class="flash-message">
            {{ session('message') }}
        </div>
    @endif
    @if ($isPending)
        <p class="detail__pending-message">
            ※承認待ちのため修正はできません。
        </p>
    @endif
</div>
@endsection