@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user/request.css') }}">
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
<div class="request">

    <h1 class="request__title">申請一覧</h1>

    {{-- タブ --}}
    <div class="request__tabs">
        <a href="{{ route('attendance_request.index') }}"
            class="request__tab {{ $activeTab === 'pending' ? 'is-active' : '' }}">
            承認待ち
        </a>

        <a href="{{ route('request.approved') }}"
            class="request__tab {{ $activeTab === 'approved' ? 'is-active' : '' }}">
            承認済み
        </a>
    </div>

    {{-- テーブル --}}
    <table class="request__table">
        <thead>
            <tr>
                <th>状態</th>
                <th>名前</th>
                <th>対象日時</th>
                <th>申請理由</th>
                <th>申請日時</th>
                <th>詳細</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($requests as $req)
                <tr>
                    <td>{{ $req->status === 'pending' ? '承認待ち' : '承認済み' }}</td>
                    <td>{{ $req->attendance->user->name }}</td>
                    <td>{{ $req->attendance->created_at->format('Y/m/d') }}</td>
                    <td>{{ $req->attendance->note ?? '—' }}</td>
                    <td>{{ $req->created_at->format('Y/m/d') }}</td>
                    <td>
                        <a href="{{ route('attendance.detail', $req->attendance_id) }}" class="request__detail-link">
                            詳細
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="request__empty">申請はありません。</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>
@endsection