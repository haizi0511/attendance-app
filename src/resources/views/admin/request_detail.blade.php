@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/request_detail.css') }}">
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

<div class="request-detail">

    <h1 class="request-detail__title">勤怠詳細</h1>

    {{-- 名前 --}}
    <div class="request-detail__row">
        <span>名前</span>
        <span>{{ $req->attendance->user->name }}</span>
    </div>

    {{-- 日付 --}}
    <div class="request-detail__row">
        <span>日付</span>
        <span>{{ $req->attendance->created_at->format('Y年 n月 j日') }}</span>
    </div>

    {{-- 出勤・退勤 --}}
    <div class="request-detail__row">
        <span>出勤・退勤</span>
        <span>
            {{ optional($req->attendance->clock_in)->format('H:i') ?? '' }}
            〜
            {{ optional($req->attendance->clock_out)->format('H:i') ?? '' }}
        </span>
    </div>

    {{-- 休憩（登録件数 + 1 を表示） --}}
    @php
        $breakCount = $req->attendance->breakTimes->count();
    @endphp

    @for ($i = 0; $i < $breakCount + 1; $i++)
        @php
            $break = $req->attendance->breakTimes[$i] ?? null;
        @endphp

        <div class="request-detail__row">
            <span>休憩{{ $i + 1 }}</span>
            <span>
                {{ optional(optional($break)->break_start)->format('H:i') ?? '' }}
                〜
                {{ optional(optional($break)->break_end)->format('H:i') ?? '' }}
            </span>
        </div>
    @endfor

    {{-- 備考 --}}
    <div class="request-detail__row">
        <span>備考</span>
        <span>{{ $req->attendance->note ?: '—' }}</span>
    </div>

    {{-- 承認ボタン or 承認済み --}}
    <div class="request-detail__action">
        @if ($req->status === 'pending')
            <form action="{{ route('admin.request.update', $req->id) }}" method="post">
                @csrf
                <button class="approve-btn">承認</button>
            </form>
        @else
            <div class="approved-label">承認済み</div>
        @endif
    </div>

</div>
@endsection
